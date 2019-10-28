<?php

namespace App\Struct\Shop;

class Status extends \App\Struct\Struct
{
    /** @var null */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    public static $mappedFields = [];
}
