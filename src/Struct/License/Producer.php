<?php

namespace App\Struct\License;

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

    protected static $mappedFields = [
    ];
}
