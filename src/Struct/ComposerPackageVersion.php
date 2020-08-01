<?php

namespace App\Struct;

class ComposerPackageVersion extends Struct
{
    public string $name;

    public string $description = '';

    public string $license = 'proprietary';

    public string $homepage;

    public string $version;

    public array $dist;

    public string $type;

    public array $extra;

    public array $require;

    public array $authors;
}
