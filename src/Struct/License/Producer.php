<?php

namespace App\Struct\License;

use App\Struct\Struct;

class Producer extends Struct
{
    public int $id;

    public string $name;

    public string $prefix;
}
