<?php

namespace App\Components;

use App\Components\Api\Client;
use App\Struct\ComposerPackageVersion;
use App\Struct\License\Binaries;

class BinaryLoader
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var MultiCurl
     */
    private $curl;

    /**
     * @var MultiCurl
     */
    private $downloadLinkCurl;

    /**
     * @var Client
     */
    private $client;

    public function __construct(Storage $storage, Client $client)
    {
        $this->curl = new MultiCurl();
        $this->downloadLinkCurl = new MultiCurl(2);
        $this->storage = $storage;
        $this->client = $client;
    }

    /**
     * @param Binaries $binary
     */
    public function add(string $pluginName, \stdClass $binary, ComposerPackageVersion $composerVersion)
    {
        if ($this->storage->hasBinary($pluginName, $binary, $composerVersion)) {
            $info = $this->storage->getBinaryInfo($pluginName, $binary, $composerVersion);

            $info = array_merge(get_object_vars($composerVersion), $info);

            foreach ($info as $k => $v) {
                $composerVersion->$k = $v;
            }
        } else {
            $shopId = $this->client->currentToken()->getShop()->id;
            $this->downloadLinkCurl->addRequest(
                Client::ENDPOINT . $composerVersion->dist['url'] . '?json=true&shopId=' . $shopId,
                null,
                [$this, 'processDownloadLink'],
                [
                    'pluginName' => $pluginName,
                    'binary' => $binary,
                    'composerVersion' => $composerVersion,
                ],
                null,
                [
                    'X-Shopware-Token: ' . $this->client->currentToken()->getToken(),
                ]
            );
        }

        $composerVersion->dist['url'] = $this->storage->generateLink($composerVersion->dist['url'], $this->client->currentToken());
    }

    public function load()
    {
        $this->downloadLinkCurl->execute();
        $this->downloadLinkCurl->reset();
        $this->curl->execute();
        $this->curl->reset();
    }

    public function processDownloadLink($response, $url, $requestInfo, $userData)
    {
        if (false === $response) {
            $userData['composerVersion']->invalid = true;

            return;
        }

        $json = json_decode($response, true);

        if (null === $json) {
            $userData['composerVersion']->invalid = true;

            return;
        }

        if (!array_key_exists('url', $json)) {
            $userData['composerVersion']->invalid = true;

            return;
        }

        $this->curl->addRequest(
            $json['url'],
            null,
            [$this, 'processFile'],
            $userData
        );
    }

    public function processFile($response, $url, $requestInfo, $userData)
    {
        if (false === $response) {
            $userData['composerVersion']->invalid = true;

            return;
        }

        try {
            $info = array_merge(get_object_vars($userData['composerVersion']), PluginReader::readFromZip($response));
        } catch (\InvalidArgumentException $e) {
            $userData['composerVersion']->invalid = true;

            return;
        }

        foreach ($info as $k => $v) {
            $userData['composerVersion']->$k = $v;
        }

        $this->storage->saveBinary($userData['pluginName'], $userData['binary'], $userData['composerVersion']);
    }
}
