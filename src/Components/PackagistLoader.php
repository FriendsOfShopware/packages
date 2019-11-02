<?php

namespace App\Components;

use App\Struct\ComposerPackageVersion;
use App\Struct\License\Binaries;
use App\Struct\License\License;
use App\Struct\License\Plugin;

class PackagistLoader
{
    private const KNOWN_BROKEN_PLUGINS = [
        'store.shopware.com/netifoundation_2.1.0' => true,
    ];

    /**
     * @var BinaryLoader
     */
    private $binaryLoader;

    public function __construct(BinaryLoader $binaryLoader)
    {
        $this->binaryLoader = $binaryLoader;
    }

    /**
     * @param License[] $licenses
     */
    public function load(array $licenses): array
    {
        $response = [
            'packages' => $this->mapLicensesToComposerPackages($licenses),
        ];

        $this->binaryLoader->load();

        return $this->removeInvalidEntries($response);
    }

    /**
     * @param License[] $licenses
     */
    private function mapLicensesToComposerPackages(array $licenses): array
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

            if (!is_array($license->plugin->binaries)) {
                $license->plugin->binaries = [$license->plugin->binaries];
            }

            $response[$packageName] = $this->convertBinaries($packageName, $license->plugin, $license->plugin->binaries);
        }

        return $response;
    }

    /**
     * @param Plugin     $plugin
     * @param Binaries[] $binaries
     */
    private function convertBinaries(string $packageName, $plugin, array $binaries): array
    {
        $versions = [];

        foreach ($binaries as $binary) {
            if (empty($binary->version)) {
                continue;
            }

            $key = $packageName . '_' . $binary->version;
            if (isset(self::KNOWN_BROKEN_PLUGINS[$key])) {
                continue;
            }

            $version = new ComposerPackageVersion();
            $version->name = $packageName;
            $version->version = $binary->version;
            $version->dist = [
                'url' => 'plugins/' . $plugin->id . '/binaries/' . $binary->id . '/file',
                'type' => 'zip',
            ];
            $version->type = 'shopware-plugin';
            $version->extra = [
                'installer-name' => $plugin->name,
            ];
            $version->require = [
                'composer/installers' => '~1.0',
            ];
            $version->authors = [
                [
                    'name' => $plugin->producer->name,
                ],
            ];

            // Shopware 1 to 5
            if ('classic' === $plugin->generation->name) {
                $version->require['shopware/shopware'] = '>=' . $binary->compatibleSoftwareVersions[0]->name;
            }

            $versions[$binary->version] = $version;

            $this->binaryLoader->add($plugin->name, $binary, $versions[$binary->version]);
        }

        return $versions;
    }

    private function removeInvalidEntries(array $response): array
    {
        foreach ($response['packages'] as $packageName => &$versions) {
            foreach ($versions as $key => $version) {
                if (isset($version->invalid)) {
                    unset($versions[$key]);
                }
            }
        }

        unset($versions);

        $response['packages'] = array_filter($response['packages']);

        return $response;
    }
}
