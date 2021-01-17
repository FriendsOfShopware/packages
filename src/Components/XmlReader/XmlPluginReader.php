<?php

declare(strict_types=1);

namespace App\Components\XmlReader;

use DOMDocument;
use DOMElement;
use DOMXPath;

class XmlPluginReader extends XmlReaderBase
{
    protected string $xsdFile = __DIR__ . '/schema/plugin.xsd';

    /**
     * This method should be overridden as main entry point to parse a xml file.
     *
     * @return array<string, mixed>
     */
    protected function parseFile(DOMDocument $xml)
    {
        $xpath = new DOMXPath($xml);
        $plugin = $xpath->query('//plugin');

        if ($plugin === false) {
            throw new \RuntimeException('Cannot find "plugin" node in xml');
        }

        /** @var DOMElement $pluginData */
        $pluginData = $plugin->item(0);
        $info = [];

        $labels = $xpath->query('//plugin/label');

        if ($labels === false) {
            throw new \RuntimeException('Cannot find "label" in xml node');
        }

        if ($label = self::parseTranslatableNodeList($labels)) {
            $info['label'] = $label;
        }

        $description = $xpath->query('//plugin/description');
        if ($description === false) {
            throw new \RuntimeException('Cannot find "description" in xml node');
        }

        if ($description = self::parseTranslatableNodeList($description)) {
            $info['description'] = $description;
        }

        $simpleFields = ['version', 'license', 'author', 'copyright', 'link'];
        foreach ($simpleFields as $simpleField) {
            if (null !== ($fieldValue = self::getElementChildValueByName($pluginData, $simpleField))) {
                $info[$simpleField] = $fieldValue;
            }
        }
        /** @var DOMElement $changelog */
        foreach ($pluginData->getElementsByTagName('changelog') as $changelog) {
            $version = $changelog->getAttribute('version');
            /** @var DOMElement $changes */
            foreach ($changelog->getElementsByTagName('changes') as $changes) {
                $lang = $changes->getAttribute('lang') ?: 'en';
                $info['changelog'][$version][$lang][] = $changes->nodeValue;
            }
        }

        $compatibilityNode = $xpath->query('//plugin/compatibility');

        if ($compatibilityNode) {
            /** @var DOMElement|null $compatibility */
            $compatibility = $compatibilityNode->item(0);

            if (null !== $compatibility) {
                $info['compatibility'] = [
                    'minVersion' => $compatibility->getAttribute('minVersion'),
                    'maxVersion' => $compatibility->getAttribute('maxVersion'),
                ];
            }
        }

        $requiredPlugins = self::getFirstChildren(
            $pluginData,
            'requiredPlugins'
        );
        if (null !== $requiredPlugins) {
            $info['requiredPlugins'] = $this->parseRequiredPlugins($requiredPlugins);
        }

        return $info;
    }

    /**
     * parse required plugins.
     *
     * @return array{'pluginName': string, 'minVersion'?: string|null, 'maxVersion'?: string|null}[]
     */
    private function parseRequiredPlugins(DOMElement $requiredPluginNode): array
    {
        $plugins = [];
        $requiredPlugins = $requiredPluginNode->getElementsByTagName('requiredPlugin');
        /** @var DOMElement $requiredPlugin */
        foreach ($requiredPlugins as $requiredPlugin) {
            $plugin = [];
            $plugin['pluginName'] = $requiredPlugin->getAttribute('pluginName');
            if ($minVersion = $requiredPlugin->getAttribute('minVersion')) {
                $plugin['minVersion'] = $minVersion;
            }
            if ($maxVersion = $requiredPlugin->getAttribute('maxVersion')) {
                $plugin['maxVersion'] = $maxVersion;
            }
            $plugins[] = $plugin;
        }

        return $plugins;
    }
}
