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
     * @param Shop      $shop
     */
    private function mapLicensesToComposerPackages(array $licenses, object $shop): array
    {
        $response = [];

        $pluginNames = array_map(function ($license) {
            return $license->plugin->name;
        }, $licenses);

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
                time() >= strtotime($license->expirationDate)) {
                continue;
            }

            $packageName = 'store.shopware.com/' . strtolower($license->plugin->name);

            $package = $databasePlugins[$packageName];

            if (null === $package) {
                continue;
            }

            if (!is_array($license->plugin->binaries)) {
                $license->plugin->binaries = [$license->plugin->binaries];
            }

            $response[$packageName] = $this->convertBinaries($packageName, $license, $package, $shop);
        }

        return $response;
    }

    /**
     * @param License $license
     * @param Shop    $shop
     */
    private function convertBinaries(string $packageName, $license, Package $package, object $shop): array
    {
        $versions = [];

        foreach ($license->plugin->binaries as $binary) {
            if (empty($binary->version)) {
                continue;
            }

            $databaseItem = null;

            /** @var Version $item */
            foreach ($package->getVersions()->toArray() as $item) {
                if ($item->getVersion() === $binary->version) {
                    $databaseItem = $item;
                    break;
                }
            }

            if (null === $databaseItem) {
                // We don't have this version. Should be fixed with next update
                continue;
            }

            $subscriptionLeft = isset($license->subscription) && strtotime($binary->creationDate) >= strtotime($license->subscription->expirationDate);

            // If shop has a active subscription all premium / advanced features are unlocked
            if (($license->plugin->isPremiumPlugin || $license->plugin->isAdvancedFeature) && $shop->hasActiveSubscription()) {
                $subscriptionLeft = false;
            }

            if ($subscriptionLeft) {
                // Subscription left
                continue;
            }

            $version = $databaseItem->toJson();
            $isOldArchiveStructure = in_array($version['type'], ['shopware-core-plugin', 'shopware-backend-plugin', 'shopware-frontend-plugin']);
            $version['name'] = $packageName;
            $version['dist'] = [
                'url' => $this->generateLink(
                    $this->client->getBinaryFilePath($license, $binary),
                    $this->client->currentToken(),
                    $isOldArchiveStructure
                ),
                'type' => 'zip',
            ];

            $versions[$binary->version] = $version;
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

        return getenv('APP_URL') . '/download?token=' . urlencode($this->encryption->encrypt($data));
    }
}
