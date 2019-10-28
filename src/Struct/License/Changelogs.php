<?php

namespace App\Struct\License;

class Changelogs extends \App\Struct\Struct
{
    /** @var int */
    public $id;

    /** @var Locale */
    public $locale;

    /** @var string */
    public $text;

    public static $mappedFields = ['locale' => 'App\Struct\License\Locale'];
}
