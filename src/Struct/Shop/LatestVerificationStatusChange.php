<?php

namespace App\Struct\Shop;

class LatestVerificationStatusChange extends \App\Struct\Struct
{
    public int $id;

    public int $shopId;

    public string $statusCreationDate;

    public PreviousStatusChange $previousStatusChange;

    public ShopDomainVerificationStatus $shopDomainVerificationStatus;

    public static $mappedFields = [
        'previousStatusChange' => 'App\Struct\Shop\PreviousStatusChange',
        'shopDomainVerificationStatus' => 'App\Struct\Shop\ShopDomainVerificationStatus',
    ];
}
