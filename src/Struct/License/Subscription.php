<?php

namespace App\Struct\License;

use App\Struct\Struct;

class Subscription extends Struct
{
    public string $id;

    public string $creationDate;

    public string $expirationDate;
}
