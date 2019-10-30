<?php
namespace App\Struct\License;

/**
 * @property integer $id
 * @property string $name
 * @property integer $parent
 * @property boolean $selectable
 * @property string $major
 * @property NULL $releaseDate
 * @property boolean $public
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

    protected static $mappedFields = [
        
    ];


}
