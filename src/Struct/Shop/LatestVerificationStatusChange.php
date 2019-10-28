<?php

namespace App\Struct\Shop;

class LatestVerificationStatusChange extends \App\Struct\Struct
{
    /** @var int */
    public $id;

    /** @var int */
    public $shopId;

    /** @var string */
    public $statusCreationDate;

    /** @var PreviousStatusChange */
    public $previousStatusChange;

    /** @var ShopDomainVerificationStatus */
    public $shopDomainVerificationStatus;

    public static $mappedFields = [
        'previousStatusChange' => 'App\Struct\Shop\PreviousStatusChange',
        'shopDomainVerificationStatus' => 'App\Struct\Shop\ShopDomainVerificationStatus',
    ];
}
