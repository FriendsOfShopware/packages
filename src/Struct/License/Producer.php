<?php

namespace App\Struct\License;

class Producer extends \App\Struct\Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $prefix;

    /** @var bool */
    public $hasCancelledContract;

    public static $mappedFields = [];
}
