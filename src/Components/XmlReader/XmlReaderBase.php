<?php

declare(strict_types=1);

namespace App\Components\XmlReader;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use InvalidArgumentException;
use Symfony\Component\Config\Util\XmlUtils;

/**
 * Class XmlReaderBase.
 */
abstract class XmlReaderBase implements XmlReaderInterface
{
    const SCOPE_LOCALE = 0;
    const SCOPE_SHOP = 1;
    const DEFAULT_LANG = 'en';

    /**
     * Map of language shortcuts to locale.
     */
    protected static array $languageMapping = [
        'de' => 'de_DE',
        'en' => 'en_GB',
    ];

    /**
     * @var string should be set in instance that extends this class
     */
    protected $xsdFile;

    /**
     * load and validate xml file - parse to array.
     */
    public function read(string $xmlFile): array
    {
        try {
            $dom = XmlUtils::loadFile($xmlFile, $this->xsdFile);
        } catch (\Exception $e) {
            throw new InvalidArgumentException(\sprintf('Unable to parse file "%s" with message %s.', $xmlFile, $e->getMessage()), $e->getCode(), $e);
        }

        return $this->parseFile($dom);
    }

    /**
     * Parses translatable node list.
     *
     * @return array|null
     */
    public static function parseTranslatableNodeList(DOMNodeList $list)
    {
        if (0 === $list->length) {
            return null;
        }
        $translations = [];
        /** @var DOMElement $item */
        foreach ($list as $item) {
            $language = $item->getAttribute('lang') ?: self::DEFAULT_LANG;
            // XSD Requires en-GB, Zend uses en_GB
            $language = \str_replace('-', '_', $language);
            $translations[$language] = \trim($item->nodeValue);
        }

        return $translations;
    }

    /**
     * Get child elements by name.
     *
     * @param mixed $name
     *
     * @return array
     */
    public static function getChildren(DOMNode $node, $name)
    {
        $children = [];
        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMElement && $child->localName === $name) {
                $children[] = $child;
            }
        }

        return $children;
    }

    /**
     * Returns first item of DOMNodeList or null.
     *
     * @param string $name
     *
     * @return DOMElement|null
     */
    public static function getFirstChildren(DOMNode $list, $name)
    {
        $children = self::getChildren($list, $name);
        if (0 === \count($children)) {
            return null;
        }

        return $children[0];
    }

    /**
     * Validates boolean attribute.
     *
     * @param string $value
     * @param bool   $defaultValue
     *
     * @return bool
     */
    public static function validateBooleanAttribute($value, $defaultValue = false)
    {
        if ('' === $value) {
            return $defaultValue;
        }

        return (bool) XmlUtils::phpize($value);
    }

    /**
     * @return array|null
     */
    public static function parseOptionsNodeList(DOMNodeList $optionsList)
    {
        if (0 === $optionsList->length) {
            return null;
        }
        $optionsItem = $optionsList->item(0);
        $optionList = $optionsItem->childNodes;
        if (0 === $optionList->length) {
            return null;
        }
        $options = [];
        /** @var DOMElement $option */
        foreach ($optionList as $option) {
            if ($option instanceof DOMElement) {
                $options[$option->nodeName] = XmlUtils::phpize($option->nodeValue);
            }
        }

        return $options;
    }

    /**
     * Returns all element child values by nodeName.
     *
     * @param string $name
     * @param bool   $throwException
     *
     * @throws InvalidArgumentException
     *
     * @return string|null
     */
    public static function getElementChildValueByName(DOMElement $element, $name, $throwException = false)
    {
        $children = $element->getElementsByTagName($name);
        if (0 === $children->length) {
            if ($throwException) {
                throw new InvalidArgumentException(\sprintf('Element with %s not found', $name));
            }

            return null;
        }

        return $children->item(0)->nodeValue;
    }

    /**
     * Validates attribute type.
     *
     * @param string $type
     * @param string $defaultValue
     *
     * @return string
     */
    public static function validateTextAttribute($type, $defaultValue = '')
    {
        if ('' === $type) {
            return $defaultValue;
        }

        return $type;
    }

    /**
     * This method should be overridden as main entry point to parse a xml file.
     *
     * @return array
     */
    abstract protected function parseFile(DOMDocument $xml);
}
