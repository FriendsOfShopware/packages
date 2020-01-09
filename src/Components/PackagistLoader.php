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

class PackagistLoader
{
    /**
     * @var PackageRepository
     */
    private $packageRepository;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Encryption
     */
    private $encryption;

    public function __construct(EntityManagerInterface $entityManager, Client $client, Encryption $encryption)
    {
        $this->packageRepository = $entityManager->getRepository(Package::class);
        $this->client = $client;
        $this->encryption = $encryption;
    }

    /**
     * @param License[] $licenses
     * @param Shop      $shop
     */
    public function load(array $licenses, object $shop): array
    {
        return [
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

            $package = $this->packageRepository->findOne($packageName);

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
            $version['name'] = $packageName;
            $version['dist'] = [
                'url' => $this->generateLink('plugins/' . $license->plugin->id . '/binaries/' . $binary->id . '/file', $this->client->currentToken()),
                'type' => 'zip',
            ];

            $versions[$binary->version] = $version;
        }

        return $versions;
    }

    private function generateLink(string $filePath, AccessToken $token): string
    {
        $data = [
            'filePath' => $filePath,
            'domain' => $token->getShop()->domain,
            'username' => $token->getUsername(),
            'password' => $token->getPassword(),
        ];

        return getenv('APP_URL') . '/download?token=' . urlencode($this->encryption->encrypt($data));
    }
}
