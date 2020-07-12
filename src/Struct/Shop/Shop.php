<?php

namespace App\Struct\Shop;

use App\Struct\Struct;

class Shop extends Struct
{
    public const TYPE_WILDCARD = 'wildcard-instance';
    public const TYPE_SHOP = 'shop';

    public int $id;

    // Base Domain ID for wildcard instances
    public int $baseId;

    public string $domain;

    public string $type;

    public string $shopwareVersion;
}
