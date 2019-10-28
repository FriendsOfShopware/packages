<?php


namespace App\Struct;


class ComposerPackageVersion extends Struct
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $version;

    /**
     * @var array
     */
    public $dist;

    /**
     * @var array
     */
    public $type;

    /**
     * @var array
     */
    public $extra;

    /**
     * @var array
     */
    public $require;
}