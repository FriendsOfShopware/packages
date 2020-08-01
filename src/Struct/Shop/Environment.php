<?php

namespace App\Struct\Shop;

class Environment extends \App\Struct\Struct
{
    public string $id;

    public string $name;

    public string $description;

    public static $mappedFields = [];
}
