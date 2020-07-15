<?php

namespace App\Struct\Shop;

class ShopDomainVerificationStatus extends \App\Struct\Struct
{
    public int $id;

    public string $name;

    public string $description;

    public static $mappedFields = [];
}
