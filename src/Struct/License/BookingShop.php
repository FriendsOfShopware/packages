<?php

namespace App\Struct\License;

use App\Struct\Struct;

/**
 * @property int     $id
 * @property Company $company
 * @property string  $domain
 */
class BookingShop extends Struct
{
    public $id = null;

    public $company = null;

    public $domain = null;

    public static $mappedFields = [
        'company' => 'App\\Struct\\License\\Company',
    ];
}
