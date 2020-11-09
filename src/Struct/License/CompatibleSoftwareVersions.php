<?php

namespace App\Struct\License;

use App\Struct\Struct;

/**
 * @property int    $id
 * @property string $name
 * @property int    $parent
 * @property bool   $selectable
 * @property string $major
 * @property null   $releaseDate
 * @property bool   $public
 */
class CompatibleSoftwareVersions extends Struct
{
    public $id;

    public $name;

    public $parent;

    public $selectable;

    public $major;

    public $releaseDate;

    public $public;
}
