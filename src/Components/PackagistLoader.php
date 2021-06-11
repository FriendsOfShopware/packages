<?php

namespace App\Components;

use App\Components\Api\AccessToken;
use App\Components\Api\Client;
use App\Entity\DependencyPackage;
use App\Entity\Package;
use App\Entity\Version;
use App\Repository\PackageRepository;
use App\Struct\License\License;
use App\Struct\Shop\Shop;
use Composer\Semver\Semver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class PackagistLoader
{
    private string $dependencyDownloadUrl;

    private string $binaryDownloadUrl;

    /**
     * @param PackageRepository<Package> $packageRepository
     */
    public function __construct(private PackageRepository $packageRepository, private Client $client, private Encryption $encryption, private RouterInterface $router)
    {
        $this->dependencyDownloadUrl = $this->router->generate('app_download_dependencydownload', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $this->binaryDownloadUrl = $this->router->generate('app_download_download', [], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @param License[] $licenses
     *
     * @return array{'notify-batch': string, 'packages': array<string, array<string, array<string, mixed>>>}
     */
    public function load(array $licenses, ResolverContext $context): array
    {
        if ($context->token->getShop() === null) {
            throw new \RuntimeException('Token needs an shop');
        }

        $body = [
            'notify-batch' => $this->router->generate('notify', [], RouterInterface::ABSOLUTE_URL),
            'packages' => $this->mapLicensesToComposerPackages($licenses, $context->token->getShop()),
        ];

        if ($context->usesDeprecatedHeader) {
            $body['warning'] = 'Usage of Token header is deprecated. Please switch to bearer auth. You can generate a new configuration in the Packages account.';
            $body['warning-versions'] = '>1.0.0';
        }

        return $body;
    }

    /**
     * @param License[] $licenses
     *
     * @return array<string, array<string, array<string, mixed>>>
     */
    private function mapLicensesToComposerPackages(array $licenses, Shop $shop): array
    {
        $response = [];

        $pluginNames = array_map(static fn ($license) => $license->plugin->name, $licenses);

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

            if (!isset($databasePlugins[$packageName])) {
                continue;
            }

            /** @var Package $package */
            $package = $databasePlugins[$packageName];

            $response[$packageName] = $this->convertBinaries($packageName, $license, $package, $shop);

            // Add dependency private packages
            /** @var Version $packageVersion */
            foreach (array_reverse($package->getVersions()->toArray()) as $packageVersion) {
                /** @var DependencyPackage $dependencyPackage */
                foreach ($packageVersion->getDependencyPackages() as $dependencyPackage) {
                    if (!isset($response[$dependencyPackage->getName()])) {
                        $response[$dependencyPackage->getName()] = [];
                    }

                    if (isset($response[$dependencyPackage->getName()][$dependencyPackage->getVersion()])) {
                        continue;
                    }

                    $composerJson = $dependencyPackage->getComposerJson();
                    $composerJson['dist'] = [
                        'url' => $this->dependencyDownloadUrl . '?token=' . urlencode($this->encryption->encrypt(['dependencyId' => $dependencyPackage->getId()])),
                        'type' => 'zip'
                    ];

                    $response[$dependencyPackage->getName()][$dependencyPackage->getVersion()] = $composerJson;
                }
            }
        }

        return $response;
    }

    /**
     * @param License $license
     *
     * @return array<string, array<string, mixed>>
     */
    private function convertBinaries(string $packageName, $license, Package $package, Shop $shop): array
    {
        $versions = [];

        $packageVersions = $package->getVersions()->toArray();
        uasort($packageVersions, static fn (Version $a, Version $b) => version_compare((string) $a->getVersion(), (string) $b->getVersion()));

        if ($shop->type === Shop::TYPE_PARTNER) {
            /** @var Version $binary */
            foreach (array_reverse($packageVersions) as $binary) {
                if (!\is_array($binary->getRequireSection())) {
                    continue;
                }

                $requireSection = $binary->getRequireSection();

                if (!isset($requireSection['shopware/core'])) {
                    continue;
                }

                if (Semver::satisfies($shop->shopwareVersion->name, $requireSection['shopware/core']) && $converted = $this->convertBinary($packageName, $license, $binary, $shop)) {
                    $versions[$binary->getVersion()] = $converted;
                    break;
                }
            }

            return $versions;
        }

        /** @var Version $binary */
        foreach (array_reverse($packageVersions) as $binary) {
            $converted = $this->convertBinary($packageName, $license, $binary, $shop);

            if ($converted === null) {
                continue;
            }

            $versions[$binary->getVersion()] = $converted;
        }

        return $versions;
    }

    /**
     * @param License $license
     * @return array<string, mixed>
     */
    private function convertBinary(string $packageName, $license, Version $binary, Shop $shop): ?array
    {
        $subscriptionLeft = isset($license->subscription) && $binary->getReleaseDate()->getTimestamp() >= strtotime($license->subscription->expirationDate);

        // If shop has a active subscription all premium / advanced features are unlocked
        if (($license->plugin->isPremiumPlugin || $license->plugin->isAdvancedFeature) && $shop->hasActiveSubscription()) {
            $subscriptionLeft = false;
        }

        if ($subscriptionLeft) {
            // Subscription left
            return null;
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

        return $version;
    }

    private function generateLink(string $filePath, AccessToken $token, bool $isOldArchive): string
    {
        if ($token->getShop() === null) {
            throw new \InvalidArgumentException('Token needs a Shop');
        }

        $data = [
            'filePath' => $filePath,
        ];

        if ($isOldArchive) {
            $data['needsRepack'] = true;
        }

        return $this->binaryDownloadUrl . '?token=' . urlencode($this->encryption->encrypt($data));
    }
}
