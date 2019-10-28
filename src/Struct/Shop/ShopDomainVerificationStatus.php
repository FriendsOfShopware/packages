<?php

namespace App\Struct\Shop;

class ShopDomainVerificationStatus extends \App\Struct\Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    public static $mappedFields = [];
}
