<?php

namespace App\Struct\Shop;

class Shop extends \App\Struct\Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $domain;

    /** @var int */
    public $companyId;

    /** @var string */
    public $companyName;

    /** @var int */
    public $ownerId;

    /** @var int */
    public $dispo;

    /** @var int */
    public $balance;

    /** @var bool */
    public $isPartnerShop;

    /** @var null */
    public $subaccount;

    /** @var SubscriptionModules[] */
    public $subscriptionModules;

    /** @var bool */
    public $isCommercial;

    /** @var string */
    public $documentComment;

    /** @var bool */
    public $activated;

    /** @var string */
    public $accountId;

    /** @var int */
    public $shopNumber;

    /** @var bool */
    public $staging;

    /** @var bool */
    public $instance;

    /** @var Environment */
    public $environment;

    /** @var string */
    public $creationDate;

    /** @var ShopwareVersion */
    public $shopwareVersion;

    /** @var string */
    public $domain_idn;

    /** @var LatestVerificationStatusChange */
    public $latestVerificationStatusChange;

    public static $mappedFields = [
        'subscriptionModules' => 'App\Struct\Shop\SubscriptionModules',
        'environment' => 'App\Struct\Shop\Environment',
        'shopwareVersion' => 'App\Struct\Shop\ShopwareVersion',
        'latestVerificationStatusChange' => 'App\Struct\Shop\LatestVerificationStatusChange',
    ];

    public function hasActiveSubscription(): bool
    {
        foreach ($this->subscriptionModules as $subscription) {
            if (strtotime($subscription->expirationDate) >= time()) {
                return true;
            }
        }

        return false;
    }
}
