<?php

namespace App\Struct\License;

use App\Struct\Struct;

/**
 * @property int          $id
 * @property BookingShop  $bookingShop
 * @property BookingShop  $licenseShop
 * @property VariantType  $variantType
 * @property string       $orderNumber
 * @property int          $price
 * @property string       $creationDate
 * @property bool         $payed
 * @property bool         $archived
 * @property int          $shopwareMajorVersion
 * @property Subscription $subscription
 * @property null         $expirationDate
 * @property Plugin       $plugin
 * @property null         $charging
 * @property bool         $disbursed
 * @property int          $quantity
 * @property string       $description
 * @property string       $licenseKey
 * @property bool         $licenseMigrated
 */
class License extends Struct
{
    public $id = null;

    public $bookingShop = null;

    public $licenseShop = null;

    public $variantType = null;

    public $orderNumber = null;

    public $price = null;

    public $creationDate = null;

    public $payed = null;

    public $archived = null;

    public $shopwareMajorVersion = null;

    public $subscription = null;

    public $expirationDate = null;

    public $plugin = null;

    public $charging = null;

    public $disbursed = null;

    public $quantity = null;

    public $description = null;

    public $licenseKey = null;

    public $licenseMigrated = null;

    public static $mappedFields = [
        'bookingShop' => 'App\\Struct\\License\\BookingShop',
        'licenseShop' => 'App\\Struct\\License\\BookingShop',
        'variantType' => 'App\\Struct\\License\\VariantType',
        'subscription' => 'App\\Struct\\License\\Subscription',
        'plugin' => 'App\\Struct\\License\\Plugin',
    ];
}
