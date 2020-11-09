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
    public $id;

    public $name;

    public $prefix;

    public $companyId;

    public static $mappedFields = [
    ];
}
