<?php

namespace App\Struct\License;

class Binaries extends \App\Struct\Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $filePath;

    /** @var string */
    public $version;

    /** @var CreationDate */
    public $creationDate;

    /** @var CompatibleSoftwareVersions */
    public $compatibleSoftwareVersions;

    /** @var bool */
    public $isLicenseCheckEnabled;

    /** @var Changelogs */
    public $changelogs;

    public static $mappedFields = [
        'creationDate' => 'App\Struct\License\CreationDate',
        'compatibleSoftwareVersions' => 'App\Struct\License\CompatibleSoftwareVersions',
        'changelogs' => 'App\Struct\License\Changelogs',
    ];
}
