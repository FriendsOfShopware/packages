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
    public $id = null;

    public $name = null;

    public $parent = null;

    public $selectable = null;

    public $major = null;

    public $releaseDate = null;

    public $public = null;
}
