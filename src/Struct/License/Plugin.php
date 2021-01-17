<?php

namespace App\Struct\License;

use App\Struct\GeneralStatus;
use App\Struct\Struct;

class Plugin extends Struct
{
    public int $id;

    public string $name;

    public string $code;

    public ?string $iconUrl;

    /**
     * @var Infos[]
     */
    public array $infos;

    public GeneralStatus $activationStatus;

    public GeneralStatus $approvalStatus;

    public GeneralStatus $generation;

    public bool $isPremiumPlugin;

    public bool $isAdvancedFeature;

    public bool $isEnterpriseAccelerator;

    public Producer $producer;

    /**
     * @var Binaries[]
     */
    public array $binaries;

    public string $creationDate;

    /**
     * @var string[]
     */
    public static array $mappedFields = [
        'infos' => Infos::class,
        'activationStatus' => GeneralStatus::class,
        'approvalStatus' => GeneralStatus::class,
        'generation' => GeneralStatus::class,
        'producer' => Producer::class,
        'binaries' => Binaries::class,
    ];
}
