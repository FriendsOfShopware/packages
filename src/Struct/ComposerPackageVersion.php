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
    public $description = '';

    /**
     * @var string
     */
    public $license = 'proprietary';

    /**
     * @var string
     */
    public $homepage;

    /**
     * @var string
     */
    public $version;

    /**
     * @var array
     */
    public $dist;

    /**
     * @var string
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

    /**
     * @var array
     */
    public $authors;
}