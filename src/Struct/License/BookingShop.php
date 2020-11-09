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
    public $id;

    public $company;

    public $domain;

    public static $mappedFields = [
        'company' => 'App\\Struct\\License\\Company',
    ];
}
