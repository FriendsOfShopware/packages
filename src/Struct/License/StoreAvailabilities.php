<?php

namespace App\Struct\License;

/**
 * @property int    $id
 * @property string $name
 * @property string $description
 */
class StoreAvailabilities extends Struct
{
    public $id = null;

    public $name = null;

    public $description = null;

    protected static $mappedFields = [
    ];
}
