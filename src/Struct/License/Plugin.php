<?php

namespace App\Struct\License;

class Plugin extends \App\Struct\Struct
{
    /** @var int */
    public $id;

    /** @var bool */
    public $active;

    /** @var string */
    public $code;

    /** @var string */
    public $name;

    /** @var string */
    public $iconPath;

    /** @var Infos */
    public $infos;

    /** @var Binaries[] */
    public $binaries;

    /** @var bool */
    public $isPremiumPlugin;

    /** @var bool */
    public $isEnterpriseAccelerator;

    /** @var bool */
    public $isAdvancedFeature;

    /** @var bool */
    public $isLicenseCheckEnabled;

    /** @var bool */
    public $hasPriceModelBuy;

    /** @var bool */
    public $isSubscriptionEnabled;

    /** @var Producer */
    public $producer;

    /** @var bool */
    public $support;

    /** @var bool */
    public $supportOnlyCommercial;

    public static $mappedFields = [
        'infos' => 'App\Struct\License\Infos',
        'binaries' => 'App\Struct\License\Binaries',
        'producer' => 'App\Struct\License\Producer',
    ];
}
