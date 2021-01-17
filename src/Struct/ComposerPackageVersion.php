<?php

namespace App\Struct;

class ComposerPackageVersion extends Struct
{
    public string $name;

    public string $description = '';

    public string $license = 'proprietary';

    public string $homepage;

    public string $version;

    /**
     * @var array<string, string> $dist
     */
    public array $dist;

    public string $type;

    /**
     * @var array<string, string> $extra
     */
    public array $extra;

    /**
     * @var array<string, string> $require
     */
    public array $require;

    /**
     * @var array<string, string> $authors
     */
    public array $authors;
}
