<?php

namespace App\Struct\License;

use App\Struct\Struct;

/**
 * @property int    $id
 * @property string $creationDate
 * @property string $expirationDate
 */
class Subscription extends Struct
{
    public $id = null;

    public $creationDate = null;

    public $expirationDate = null;
}
