<?php

namespace App\Struct\License;

use App\Struct\Struct;

/**
 * @property int         $id
 * @property VariantType $locale
 * @property string      $name
 */
class Infos extends Struct
{
    public $id = null;

    public $locale = null;

    public $name = null;
}
