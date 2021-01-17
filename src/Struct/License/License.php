<?php

namespace App\Struct\License;

use App\Struct\GeneralStatus;
use App\Struct\Struct;

class License extends Struct
{
    public int $id;

    public GeneralStatus $variantType;

    public string $creationDate;

    public bool $archived;

    public Subscription $subscription;

    public string $expirationDate;

    public Plugin $plugin;

    public static array $mappedFields = [
        'variantType' => GeneralStatus::class,
        'subscription' => Subscription::class,
        'plugin' => Plugin::class,
    ];
}
