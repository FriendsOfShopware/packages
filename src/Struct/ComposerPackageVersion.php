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
     * @var array<string, string>
     */
    public array $dist;

    public string $type;

    /**
     * @var array<string, string>
     */
    public array $extra;

    /**
     * @var array<string, string>
     */
    public array $require;

    /**
     * @var array<string, string>
     */
    public array $authors;
}
