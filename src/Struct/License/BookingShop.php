<?php

namespace App\Struct\License;

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

    protected static $mappedFields = [
        'company' => 'App\\Struct\\License\\Company',
    ];
}
