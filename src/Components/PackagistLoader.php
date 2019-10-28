<?php

namespace App\Components;

use App\Struct\ComposerPackageVersion;
use App\Struct\License\Binaries;
use App\Struct\License\License;

class PackagistLoader
{
    /**
     * @var BinaryLoader
     */
    private $binaryLoader;

    /**
     * @var Client|null
     */
    private $client;

    public function __construct(BinaryLoader $binaryLoader, Client $client)
    {
        $this->binaryLoader = $binaryLoader;
        $this->client = $client;
    }

    public function load(string $domain, string $username, string $password): array
    {
        $this->client->login($username, $password, $domain);
        $licenses = $this->client->getLicenses();

        $response = [
            'packages' => $this->mapLicensesToComposerPackages($licenses, $this->client)
        ];

        $this->binaryLoader->load();

        return $this->removeInvalidEntries($response);
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param License[] $licenses
     */
    private function mapLicensesToComposerPackages(array $licenses): array
    {
        $response = [];

        foreach ($licenses as $licens) {
            if ($licens->type->id === 1 || $licens->isExpired || !isset($licens->plugin)) {
                continue;
            }
            $packageName = 'store.shopware.com/' . strtolower($licens->plugin->name);

            $response[$packageName] = $this->convertBinaries($packageName, $licens->plugin->name, $licens->plugin->binaries);
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
                'url' => $binary->filePath . '?json=true&shopId=' . $this->client->getShop()->id,
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

            $this->binaryLoader->add($pluginName, $binary, $versions[$binary->version], $this->client);
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