<?php

namespace App\Components;

use App\Components\Api\AccessToken;
use App\Components\Api\Client;
use App\Entity\Package;
use App\Entity\Version;
use App\Repository\PackageRepository;
use App\Struct\License\License;
use App\Struct\Shop\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class PackagistLoader
{
    private PackageRepository $packageRepository;

    private Client $client;

    private Encryption $encryption;

    private RouterInterface $router;

    public function __construct(EntityManagerInterface $entityManager, Client $client, Encryption $encryption, RouterInterface $router)
    {
        $this->packageRepository = $entityManager->getRepository(Package::class);
        $this->client = $client;
        $this->encryption = $encryption;
        $this->router = $router;
    }

    /**
     * @param License[] $licenses
     * @param Shop      $shop
     */
    public function load(array $licenses, object $shop): array
    {
        return [
            'notify-batch' => $this->router->generate('notify', [], RouterInterface::ABSOLUTE_URL),
            'packages' => $this->mapLicensesToComposerPackages($licenses, $shop),
        ];
    }

    /**
     * @param License[] $licenses
     */
    private function mapLicensesToComposerPackages(array $licenses, Shop $shop): array
    {
        $response = [];

        $pluginNames = \array_map(static fn ($license) => $license->plugin->name, $licenses);

        $databasePlugins = $this->packageRepository->findPackagesForLicenses($pluginNames);

        foreach ($licenses as $license) {
            // Don't list archived plugins
            if ($license->archived || !isset($license->plugin)) {
                continue;
            }

            // Don't list expired test plugins
            if (
                'test' === $license->variantType->name &&
                !empty($license->expirationDate) &&
                \time() >= \strtotime($license->expirationDate)) {
                continue;
            }

            $packageName = 'store.shopware.com/' . \strtolower($license->plugin->name);

            if (!isset($databasePlugins[$packageName])) {
                continue;
            }

            $package = $databasePlugins[$packageName];

            $response[$packageName] = $this->convertBinaries($packageName, $license, $package, $shop);
        }

        return $response;
    }

    /**
     * @param License $license
     */
    private function convertBinaries(string $packageName, $license, Package $package, Shop $shop): array
    {
        $versions = [];

        /** @var Version $binary */
        foreach (\array_reverse($package->getVersions()->toArray()) as $binary) {
            $subscriptionLeft = isset($license->subscription) && $binary->getReleaseDate()->getTimestamp() >= \strtotime($license->subscription->expirationDate);

            // If shop has a active subscription all premium / advanced features are unlocked
            if (($license->plugin->isPremiumPlugin || $license->plugin->isAdvancedFeature) && $shop->hasActiveSubscription()) {
                $subscriptionLeft = false;
            }

            if ($subscriptionLeft) {
                // Subscription left
                continue;
            }

            $version = $binary->toJson();
            $isOldArchiveStructure = \in_array($version['type'], ['shopware-core-plugin', 'shopware-backend-plugin', 'shopware-frontend-plugin']);
            $version['name'] = $packageName;
            $version['dist'] = [
                'url' => $this->generateLink(
                    $this->client->getBinaryFilePath($license, $binary),
                    $this->client->currentToken(),
                    $isOldArchiveStructure
                ),
                'type' => 'zip',
            ];

            $versions[$binary->getVersion()] = $version;

            // Wildcard have only one version (latest)
            if ($shop->type === Shop::TYPE_PARTNER) {
                break;
            }
        }

        return $versions;
    }

    private function generateLink(string $filePath, AccessToken $token, bool $isOldArchive): string
    {
        $data = [
            'filePath' => $filePath,
            'domain' => $token->getShop()->domain,
            'username' => $token->getUsername(),
            'password' => $token->getPassword(),
            'userId' => $token->getUserId(),
        ];

        if ($isOldArchive) {
            $data['needsRepack'] = true;
        }

        return \getenv('APP_URL') . '/download?token=' . \urlencode($this->encryption->encrypt($data));
    }
}
