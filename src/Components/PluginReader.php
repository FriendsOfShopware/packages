<?php


namespace App\Components;

use App\Components\XmlReader\XmlPluginReader;

class PluginReader
{
    public static function readFromZip(string $content)
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'plugin');
        file_put_contents($tmpFile, $content);

        $zip = new \ZipArchive();
        $zip->open($tmpFile);
        $folderPath = str_replace('\\', '/', $zip->statIndex(0)['name']);
        $pos = strpos($folderPath, '/');
        $path = substr($folderPath, 0, $pos);

        switch ($path) {
            case 'Frontend':
            case 'Backend':
            case 'Core':
                $zip->close();
                unlink($tmpFile);
                return [
                    'type' => 'shopware-' . strtolower($path) . '-plugin',
                ];
            default:
                return self::readNewPluginSystem($zip, $tmpFile, $path);
        }
    }

    private static function readNewPluginSystem(\ZipArchive $archive, string $tmpFile, string $pluginName)
    {
        $data = [
            'type' => 'shopware-plugin'
        ];

        $reader = new XmlPluginReader();

        $extractLocation = sys_get_temp_dir() . '/' . uniqid('location', true);
        mkdir($extractLocation);

        $archive->extractTo($extractLocation);
        $archive->close();

        if (file_exists($extractLocation . '/' . $pluginName . '/plugin.xml')) {
            $xml = $reader->read($extractLocation . '/' . $pluginName . '/plugin.xml');

            if (isset($xml['requiredPlugins'])) {
                foreach ($xml['requiredPlugins'] as $requiredPlugin) {
                    $data['require']['store.shopware.com/' . strtolower($requiredPlugin['pluginName'])] = '>=' . $requiredPlugin['minVersion'];
                }
            }

            if (isset($xml['compatibility']['minVersion'])) {
                $data['require']['shopware/shopware'] = '>=' . $xml['compatibility']['minVersion'];
            }

            if (isset($xml['label']['en'])) {
                $data['description'] = $xml['label']['en'];
            }
        } elseif (file_exists($extractLocation . '/' . $pluginName . '/composer.json')) {
            $composerJson = json_decode(file_get_contents($extractLocation . '/' . $pluginName . '/composer.json'), true);

            if (isset($composerJson['type'])) {
                $data['type'] = $composerJson['type'];
            }

            if (isset($composerJson['description'])) {
                $data['description'] = $composerJson['description'];
            }

            if (isset($composerJson['extra'])) {
                $data['extra'] = $composerJson['extra'];
            }
        }

        self::rmDir($extractLocation);
        unlink($tmpFile);

        // Clear require, default is composer/installers
        if ($data['type'] === 'shopware-platform-plugin' && !isset($data['require'])) {
            $data['require'] = [];
        }

        return $data;
    }

    private static function rmDir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object))
                        self::rmDir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            rmdir($dir);
        }
    }
}