<?php

namespace App\Struct\License;

/**
 * @property int                 $id
 * @property string              $name
 * @property string              $code
 * @property string              $iconUrl
 * @property Infos               $infos
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
    public $id = null;

    public $name = null;

    public $code = null;

    public $iconUrl = null;

    public $infos = null;

    public $storeAvailabilities = null;

    public $activationStatus = null;

    public $approvalStatus = null;

    public $generation = null;

    public $isPremiumPlugin = null;

    public $isAdvancedFeature = null;

    public $isEnterpriseAccelerator = null;

    public $hasPriceModelBuy = null;

    public $isSubscriptionEnabled = null;

    public $support = null;

    public $supportOnlyCommercial = null;

    public $producer = null;

    public $binaries = null;

    protected static $mappedFields = [
        'infos' => 'App\\Struct\\License\\Infos',
        'storeAvailabilities' => 'App\\Struct\\License\\StoreAvailabilities',
        'activationStatus' => 'App\\Struct\\License\\StoreAvailabilities',
        'approvalStatus' => 'App\\Struct\\License\\ApprovalStatus',
        'generation' => 'App\\Struct\\License\\Generation',
        'producer' => 'App\\Struct\\License\\Producer',
        'binaries' => 'App\\Struct\\License\\Binaries',
    ];
}
