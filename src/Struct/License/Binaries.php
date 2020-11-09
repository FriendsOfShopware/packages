<?php

namespace App\Struct\License;

use App\Struct\Struct;

/**
 * @property int                          $id
 * @property string                       $name
 * @property string                       $localPath
 * @property string                       $remoteLink
 * @property string                       $version
 * @property Status                       $status
 * @property CompatibleSoftwareVersions[] $compatibleSoftwareVersions
 * @property Changelogs                   $changelogs
 * @property string                       $creationDate
 * @property string                       $lastChangeDate
 * @property Archives                     $archives
 * @property bool                         $ionCubeEncrypted
 * @property bool                         $licenseCheckRequired
 */
class Binaries extends Struct
{
    public $id;

    public $name;

    public $localPath;

    public $remoteLink;

    public $version;

    public $status;

    public $compatibleSoftwareVersions;

    public $changelogs;

    public $creationDate;

    public $lastChangeDate;

    public $archives;

    public $ionCubeEncrypted;

    public $licenseCheckRequired;

    public static $mappedFields = [
        'status' => 'App\\Struct\\License\\Status',
        'compatibleSoftwareVersions' => 'App\\Struct\\License\\CompatibleSoftwareVersions',
        'changelogs' => 'App\\Struct\\License\\Changelogs',
        'archives' => 'App\\Struct\\License\\Archives',
    ];
}
