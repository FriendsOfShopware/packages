<?php

namespace App\Struct\Shop;

class Shop extends \App\Struct\Struct
{
    const TYPE_PARTNER = 'partner';

    public int $id;

    public string $domain;

    public int $companyId;

    public string $companyName;

    public int $ownerId;

    public int $dispo;

    public int $balance;

    public bool $isPartnerShop;

    /**
     * @var null
     */
    public $subaccount;

    /** @var SubscriptionModules[] */
    public array $subscriptionModules;

    public bool $isCommercial;

    public string $documentComment;

    public bool $activated;

    public string $accountId;

    public int $shopNumber;

    public bool $staging;

    public bool $instance;

    public Environment $environment;

    public string $creationDate;

    public ShopwareVersion $shopwareVersion;

    public string $domain_idn;

    public LatestVerificationStatusChange $latestVerificationStatusChange;

    public string $type;

    public int $baseId;

    public static $mappedFields = [
        'subscriptionModules' => 'App\Struct\Shop\SubscriptionModules',
        'environment' => 'App\Struct\Shop\Environment',
        'shopwareVersion' => 'App\Struct\Shop\ShopwareVersion',
        'latestVerificationStatusChange' => 'App\Struct\Shop\LatestVerificationStatusChange',
    ];

    public function hasActiveSubscription(): bool
    {
        foreach ($this->subscriptionModules as $subscription) {
            if (\strtotime($subscription->expirationDate) >= \time()) {
                return true;
            }
        }

        return false;
    }
}
