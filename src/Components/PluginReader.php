<?php

namespace App\Components;

use App\Components\XmlReader\XmlPluginReader;
use App\Entity\Version;
use Symfony\Component\Filesystem\Filesystem;

class PluginReader
{
    public static function readFromZip(string $content, Version $version): void
    {
        $tmpFile = \tempnam(\sys_get_temp_dir(), 'plugin');

        if ($tmpFile === false) {
            throw new \RuntimeException('Cannot generate tmp file');
        }

        \file_put_contents($tmpFile, $content);

        $zip = new \ZipArchive();
        if ($zip->open($tmpFile) !== true) {
            throw new \InvalidArgumentException('Invalid zip file');
        }

        $zipIndex = $zip->statIndex(0);

        if ($zipIndex === false) {
            throw new \InvalidArgumentException('Invalid zip file');
        }

        $folderPath = \str_replace('\\', '/', $zipIndex['name']);
        $pos = \strpos($folderPath, '/');

        if ($pos === false) {
            throw new \InvalidArgumentException('Zip is wrong packed');
        }

        $path = \substr($folderPath, 0, $pos);

        switch ($path) {
            case 'Frontend':
            case 'Backend':
            case 'Core':
                $zip->close();
                \unlink($tmpFile);
                $version->setType('shopware-' . \strtolower($path) . '-plugin');
                break;
            default:
                self::readNewPluginSystem($zip, $tmpFile, $path, $version);
        }
    }

    private static function readNewPluginSystem(\ZipArchive $archive, string $tmpFile, string $pluginName, Version $version): void
    {
        $version->setType('shopware-plugin');

        $reader = new XmlPluginReader();

        $extractLocation = \sys_get_temp_dir() . '/' . \uniqid('location', true);
        if (!\mkdir($extractLocation) && !\is_dir($extractLocation)) {
            throw new \RuntimeException(\sprintf('Directory "%s" was not created', $extractLocation));
        }

        $archive->extractTo($extractLocation);
        $archive->close();

        if (\file_exists($extractLocation . '/' . $pluginName . '/plugin.xml')) {
            $xml = $reader->read($extractLocation . '/' . $pluginName . '/plugin.xml');

            if (isset($xml['requiredPlugins'])) {
                foreach ($xml['requiredPlugins'] as $requiredPlugin) {
                    $requiredPluginName = \strtolower($requiredPlugin['pluginName']);

                    if ($requiredPluginName === 'cron') {
                        continue;
                    }

                    if (isset($requiredPlugin['minVersion'])) {
                        $version->addRequire('store.shopware.com/' . $requiredPluginName, '>=' . $requiredPlugin['minVersion']);
                    } else {
                        $version->addRequire('store.shopware.com/' . $requiredPluginName, '*');
                    }
                }
            }

            if (isset($xml['label']['en'])) {
                $version->setDescription($xml['label']['en']);
            }

            if (isset($xml['license'])) {
                $version->setLicense($xml['license']);
            }

            if (isset($xml['link'])) {
                $version->setHomepage($xml['link']);
            }
        } elseif (\file_exists($extractLocation . '/' . $pluginName . '/composer.json')) {
            $composerJsonBody = \file_get_contents($extractLocation . '/' . $pluginName . '/composer.json');

            if ($composerJsonBody === false) {
                throw new \RuntimeException('Cannot find composer.json in plugin');
            }

            $composerJson = \json_decode($composerJsonBody, true, 512, \JSON_THROW_ON_ERROR);

            if (isset($composerJson['type'])) {
                $version->setType($composerJson['type']);
            }

            if (isset($composerJson['description'])) {
                $version->setDescription($composerJson['description']);
            }

            if (isset($composerJson['extra'])) {
                $version->setExtra($composerJson['extra']);
            }

            if (isset($composerJson['homepage'])) {
                $version->setHomepage($composerJson['homepage']);
            }

            if (isset($composerJson['authors'])) {
                $version->setAuthors($composerJson['authors']);
            }

            if (isset($composerJson['require'])) {
                $version->setRequireSection($composerJson['require']);
            } elseif ($version->getType() === 'shopware-platform-plugin') {
                $version->setRequireSection([]);
            }

            if (isset($composerJson['autoload'])) {
                $version->setAutoload($composerJson['autoload']);
            }

            if (isset($composerJson['autoload-dev'])) {
                $version->setAutoloadDev($composerJson['autoload-dev']);
            }

            $version->setComposerJson($composerJson);
        }

        $fs = new Filesystem();
        $fs->remove($extractLocation);
        \unlink($tmpFile);
    }
}
