<?php

namespace App\Struct\Shop;

class Status extends \App\Struct\Struct
{
    /** @var null */
    public $id;

    public string $name;

    public string $description;

    public static $mappedFields = [];
}
