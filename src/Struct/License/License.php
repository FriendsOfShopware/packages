<?php

namespace App\Struct\License;

use App\Struct\Struct;

class License extends Struct
{
    public int $id;

    public string $name;

    public string $code;

    /** @var Binaries[] */
    public array $versions;

    public static $mappedFields = [
        'versions' => Binaries::class,
    ];

    public static $arrayFields = ['versions'];
}
