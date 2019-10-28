<?php

namespace App\Struct\License;

class CreationDate extends \App\Struct\Struct
{
    /** @var string */
    public $date;

    /** @var int */
    public $timezone_type;

    /** @var string */
    public $timezone;

    public static $mappedFields = [];
}
