<?php

namespace App\Struct\Shop;

class ShopwareVersion extends \App\Struct\Struct
{
    public int $id;

    public string $name;

    public int $parent;

    public bool $selectable;

    public string $major;

    /**
     * @var null
     */
    public $releaseDate;

    public bool $public;

    public static $mappedFields = [];
}
