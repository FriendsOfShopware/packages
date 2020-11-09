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
    public $id;

    public $bookingShop;

    public $licenseShop;

    public $variantType;

    public $orderNumber;

    public $price;

    public $creationDate;

    public $payed;

    public $archived;

    public $shopwareMajorVersion;

    public $subscription;

    public $expirationDate;

    public $plugin;

    public $charging;

    public $disbursed;

    public $quantity;

    public $description;

    public $licenseKey;

    public $licenseMigrated;

    public static $mappedFields = [
        'bookingShop' => 'App\\Struct\\License\\BookingShop',
        'licenseShop' => 'App\\Struct\\License\\BookingShop',
        'variantType' => 'App\\Struct\\License\\VariantType',
        'subscription' => 'App\\Struct\\License\\Subscription',
        'plugin' => 'App\\Struct\\License\\Plugin',
    ];
}
