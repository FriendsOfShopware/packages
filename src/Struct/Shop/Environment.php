<?php

namespace App\Struct\Shop;

class Environment extends \App\Struct\Struct
{
    /** @var string */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    public static $mappedFields = [];
}
