<?php

declare(strict_types=1);

namespace App\Components\XmlReader;

interface XmlReaderInterface
{
    /**
     * @return array{'label': array{'de': string, 'en': string}, 'license': string, 'link': string, 'requiredPlugins': array{'pluginName': string, 'minVersion': string}[]|null}
     */
    public function read(string $xmlFile): array;
}
