<?php

namespace App\Struct\Shop;

class PreviousStatusChange extends \App\Struct\Struct
{
    public int $id;

    public int $shopId;

    public string $statusCreationDate;

    /** @var null */
    public $previousStatusChange;

    public ShopDomainVerificationStatus $shopDomainVerificationStatus;

    public static $mappedFields = ['shopDomainVerificationStatus' => 'App\Struct\Shop\ShopDomainVerificationStatus'];
}
