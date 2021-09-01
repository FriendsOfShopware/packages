<?php

namespace App\Struct\Shop;

use App\Struct\GeneralStatus;
use App\Struct\Struct;

class Shop extends Struct
{
    public const TYPE_PARTNER = 'partner';

    public int $id;

    public string $domain;

    public int $companyId;

    public string $companyName;

    public bool $isPartnerShop;

    public ?string $accountId;

    public string $creationDate;

    public string $domain_idn;

    public string $type;

    public int $baseId;

    public bool $staging;

    public GeneralStatus $shopwareVersion;

    /**
     * @var SubscriptionModules[]
     */
    public array $subscriptionModules;

    public static array $mappedFields = [
        'subscriptionModules' => SubscriptionModules::class,
        'shopwareVersion' => GeneralStatus::class,
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
