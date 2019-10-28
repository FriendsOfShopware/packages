<?php

namespace App\Struct\Shop;

class PreviousStatusChange extends \App\Struct\Struct
{
    /** @var int */
    public $id;

    /** @var int */
    public $shopId;

    /** @var string */
    public $statusCreationDate;

    /** @var null */
    public $previousStatusChange;

    /** @var ShopDomainVerificationStatus */
    public $shopDomainVerificationStatus;

    public static $mappedFields = ['shopDomainVerificationStatus' => 'App\Struct\Shop\ShopDomainVerificationStatus'];
}
