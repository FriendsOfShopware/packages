<?php

namespace App\Components;

use App\Struct\ComposerPackageVersion;
use App\Struct\License\Binaries;
use App\Struct\License\License;
use App\Struct\Shop\Shop;

class PackagistLoader
{
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
            'packages' => $this->mapLicensesToComposerPackages($licenses)
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
            if ($license->type->id === 1 || $license->isExpired || !isset($license->plugin)) {
                continue;
            }
            $packageName = 'store.shopware.com/' . strtolower($license->plugin->name);

            $response[$packageName] = $this->convertBinaries($packageName, $license->plugin->name, $license->plugin->binaries);
        }

        return $response;
    }

    /**
     * @param Binaries[] $binaries
     */
    private function convertBinaries(string $packageName, string $pluginName, array $binaries): array
    {
        $versions = [];

        foreach ($binaries as $binary) {
            $version = new ComposerPackageVersion();
            $version->name = $packageName;
            $version->version = $binary->version;
            $version->dist = [
                'url' => substr($binary->filePath, 1),
                'type' => 'zip'
            ];
            $version->type = 'shopware-plugin';
            $version->extra = [
                'installer-name' => $pluginName
            ];
            $version->require = [
                'composer/installers' => '~1.0'
            ];

            $versions[$binary->version] = $version;

            $this->binaryLoader->add($pluginName, $binary, $versions[$binary->version]);
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