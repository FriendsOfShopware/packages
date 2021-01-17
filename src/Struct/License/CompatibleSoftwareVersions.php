<?php

namespace App\Struct\License;

use App\Struct\Struct;

class CompatibleSoftwareVersions extends Struct
{
    public int $id;

    public string $name;

    public ?int $parent;

    public bool $selectable;

    public ?string $major;

    public ?string $releaseDate;

    public bool $public;
}
