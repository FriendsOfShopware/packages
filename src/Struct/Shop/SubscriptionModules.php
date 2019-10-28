<?php

namespace App\Struct\Shop;

class SubscriptionModules extends \App\Struct\Struct
{
    /** @var int */
    public $id;

    /** @var Module */
    public $module;

    /** @var Status */
    public $status;

    /** @var string */
    public $expirationDate;

    /** @var string */
    public $creationDate;

    /** @var bool */
    public $monthlyPayment;

    /** @var int */
    public $durationInMonths;

    /** @var DurationOptions */
    public $durationOptions;

    /** @var bool */
    public $automaticExtension;

    public static $mappedFields = [
        'module' => 'App\Struct\Shop\Module',
        'status' => 'App\Struct\Shop\Status',
        'durationOptions' => 'App\Struct\Shop\DurationOptions',
    ];
}
