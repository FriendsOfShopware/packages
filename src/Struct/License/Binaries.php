<?php

namespace App\Struct\License;

use App\Struct\GeneralStatus;
use App\Struct\Struct;

class Binaries extends Struct
{
    public ?int $id;

    public ?string $name;

    public ?string $remoteLink;

    public ?string $version;

    public ?GeneralStatus $status;

    /**
     * @var CompatibleSoftwareVersions[]
     */
    public array $compatibleSoftwareVersions;

    /**
     * @var Changelogs[]
     */
    public array $changelogs;

    public ?string $creationDate;

    public string $lastChangeDate;

    /**
     * @var Archives[]
     */
    public array $archives;

    /**
     * @var string[]
     */
    public static array $mappedFields = [
        'status' => GeneralStatus::class,
        'compatibleSoftwareVersions' => CompatibleSoftwareVersions::class,
        'changelogs' => Changelogs::class,
        'archives' => Archives::class,
    ];
}
