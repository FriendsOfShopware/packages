<?php

namespace App\Struct\License;

use App\Struct\GeneralStatus;
use App\Struct\Struct;

class Changelogs extends Struct
{
    public int $id;

    public GeneralStatus $locale;

    public string $text;

    /**
     * @var string[]
     */
    public static array $mappedFields = [
        'locale' => GeneralStatus::class,
    ];
}
