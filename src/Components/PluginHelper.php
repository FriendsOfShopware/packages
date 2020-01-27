<?php

namespace App\Components;

class PluginHelper
{
    public static function getPackageName(string $producerPrefix, string $pluginName): string
    {
        $pluginNameWithPrefix = substr($pluginName, 0, strlen($producerPrefix));

        return strtolower(sprintf('%s/%s', $producerPrefix, self::camelCaseToDashes($pluginNameWithPrefix)));
    }

    private static function camelCaseToDashes(string $str): string
    {
        return strtolower(preg_replace('/([A-Z])/', '-$1', $str));
    }
}
