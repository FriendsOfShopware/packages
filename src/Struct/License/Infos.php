<?php

namespace App\Struct\License;

use App\Struct\GeneralStatus;
use App\Struct\Struct;

class Infos extends Struct
{
    public int $id;

    public GeneralStatus $locale;

    public ?string $name;

    public ?string $description;

    public ?string $shortDescription;

    /**
     * @var string[]
     */
    public static array $mappedFields = [
        'locale' => GeneralStatus::class,
    ];
}
