<?php
namespace App\Struct\License;

/**
 * @property integer $id
 * @property VariantType $locale
 * @property string $text
 */
class Changelogs extends Struct
{

    public $id = null;

    public $locale = null;

    public $text = null;

    protected static $mappedFields = [
        'locale' => 'App\\Struct\\License\\VariantType',
    ];


}
