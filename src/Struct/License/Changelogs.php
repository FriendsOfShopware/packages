<?php

namespace App\Struct\License;

use App\Struct\Struct;

/**
 * @property int         $id
 * @property VariantType $locale
 * @property string      $text
 */
class Changelogs extends Struct
{
    public $id = null;

    public $locale = null;

    public $text = null;

    public static $mappedFields = [
        'locale' => 'App\\Struct\\License\\VariantType',
    ];
}
