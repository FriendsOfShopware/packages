<?php

namespace App\Struct\Shop;

class SubscriptionModules extends \App\Struct\Struct
{
    public int $id;

    public Module $module;

    public Status $status;

    public string $expirationDate;

    public string $creationDate;

    public bool $monthlyPayment;

    public int $durationInMonths;

    public bool $automaticExtension;

    public static $mappedFields = [
        'module' => 'App\Struct\Shop\Module',
        'status' => 'App\Struct\Shop\Status'
    ];
}
