<?php

namespace App\Struct\License;

use App\Struct\Struct;

/**
 * @property int                 $id
 * @property string              $name
 * @property string              $code
 * @property string              $iconUrl
 * @property Infos[]             $infos
 * @property StoreAvailabilities $storeAvailabilities
 * @property StoreAvailabilities $activationStatus
 * @property ApprovalStatus      $approvalStatus
 * @property Generation          $generation
 * @property bool                $isPremiumPlugin
 * @property bool                $isAdvancedFeature
 * @property bool                $isEnterpriseAccelerator
 * @property bool                $hasPriceModelBuy
 * @property bool                $isSubscriptionEnabled
 * @property bool                $support
 * @property bool                $supportOnlyCommercial
 * @property Producer            $producer
 * @property Binaries[]          $binaries
 */
class Plugin extends Struct
{
    public $id;

    public $name;

    public $code;

    public $iconUrl;

    public $infos;

    public $storeAvailabilities;

    public $activationStatus;

    public $approvalStatus;

    public $generation;

    public $isPremiumPlugin;

    public $isAdvancedFeature;

    public $isEnterpriseAccelerator;

    public $hasPriceModelBuy;

    public $isSubscriptionEnabled;

    public $support;

    public $supportOnlyCommercial;

    public $producer;

    public $binaries;

    public string $creationDate;

    public static $mappedFields = [
        'infos' => 'App\\Struct\\License\\Infos',
        'storeAvailabilities' => 'App\\Struct\\License\\StoreAvailabilities',
        'activationStatus' => 'App\\Struct\\License\\StoreAvailabilities',
        'approvalStatus' => 'App\\Struct\\License\\ApprovalStatus',
        'generation' => 'App\\Struct\\License\\Generation',
        'producer' => 'App\\Struct\\License\\Producer',
        'binaries' => 'App\\Struct\\License\\Binaries',
    ];
}
