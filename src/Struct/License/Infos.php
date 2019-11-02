<?php

namespace App\Struct\License;

/**
 * @property int         $id
 * @property VariantType $locale
 * @property string      $name
 */
class Infos extends Struct
{
    public $id = null;

    public $locale = null;

    public $name = null;

    protected static $mappedFields = [
        'locale' => 'App\\Struct\\License\\VariantType',
    ];
}
