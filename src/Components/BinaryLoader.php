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
     * @var Client
     */
    private $client;

    public function __construct(Storage $storage, Client $client)
    {
        $this->curl = new MultiCurl();
        $this->storage = $storage;
        $this->client = $client;
    }

    public function add(string $pluginName, Binaries $binary, ComposerPackageVersion $composerVersion)
    {
        if ($this->storage->hasBinary($pluginName, $binary)) {
            $info = $this->storage->getBinaryInfo($pluginName, $binary);

            $info = array_merge(get_object_vars($composerVersion), $info);

            foreach ($info as $k => $v) {
                $composerVersion->$k = $v;
            }
        } else {
            $binaryLink = $this->client->fetchDownloadLink($composerVersion->dist['url']);

            if (!$binaryLink) {
                $composerVersion->invalid = true;
                return;
            }

            $this->curl->addRequest(
                $binaryLink,
                null,
                [$this, 'processFile'],
                [
                    'pluginName' => $pluginName,
                    'binary' => $binary,
                    'composerVersion' => $composerVersion
                ]
            );
        }

        $composerVersion->dist['url'] = $this->storage->generateLink($binary, $this->client->currentToken());
    }

    public function load()
    {
        $this->curl->execute();
        $this->curl->reset();
    }

    public function processFile($response, $url, $requestInfo, $userData)
    {
        if ($response === false) {
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

        $this->storage->saveBinary($userData['pluginName'], $userData['binary'], $info);
    }
}