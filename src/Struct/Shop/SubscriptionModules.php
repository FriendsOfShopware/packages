<?php

namespace App\Struct\Shop;

use App\Struct\Struct;

class SubscriptionModules extends Struct
{
    public int $id;

    public string $expirationDate;

    public string $creationDate;
}
