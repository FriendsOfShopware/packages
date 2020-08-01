<?php

namespace App\Struct\License;

use App\Struct\Struct;

/**
 * @property int    $id
 * @property string $name
 * @property string $prefix
 * @property int    $companyId
 */
class Producer extends Struct
{
    public $id = null;

    public $name = null;

    public $prefix = null;

    public $companyId = null;

    public static $mappedFields = [
    ];
}
