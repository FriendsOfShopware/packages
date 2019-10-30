<?php
namespace App\Struct\License;

/**
 * @property integer $id
 * @property BookingShop $bookingShop
 * @property BookingShop $licenseShop
 * @property VariantType $variantType
 * @property string $orderNumber
 * @property integer $price
 * @property string $creationDate
 * @property boolean $payed
 * @property boolean $archived
 * @property integer $shopwareMajorVersion
 * @property Subscription $subscription
 * @property NULL $expirationDate
 * @property Plugin $plugin
 * @property NULL $charging
 * @property boolean $disbursed
 * @property integer $quantity
 * @property string $description
 * @property string $licenseKey
 * @property boolean $licenseMigrated
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

    protected static $mappedFields = [
        'bookingShop' => 'App\\Struct\\License\\BookingShop',
        'licenseShop' => 'App\\Struct\\License\\BookingShop',
        'variantType' => 'App\\Struct\\License\\VariantType',
        'subscription' => 'App\\Struct\\License\\Subscription',
        'plugin' => 'App\\Struct\\License\\Plugin',
    ];


}
