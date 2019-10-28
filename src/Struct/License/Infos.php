<?php

namespace App\Struct\License;

class Infos extends \App\Struct\Struct
{
    /** @var int */
    public $id;

    /** @var Locale */
    public $locale;

    /** @var string */
    public $name;

    public static $mappedFields = ['locale' => 'App\Struct\License\Locale'];
}
