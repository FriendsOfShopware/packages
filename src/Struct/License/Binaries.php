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
    public $id = null;

    public $name = null;

    public $localPath = null;

    public $remoteLink = null;

    public $version = null;

    public $status = null;

    public $compatibleSoftwareVersions = null;

    public $changelogs = null;

    public $creationDate = null;

    public $lastChangeDate = null;

    public $archives = null;

    public $ionCubeEncrypted = null;

    public $licenseCheckRequired = null;

    public static $mappedFields = [
        'status' => 'App\\Struct\\License\\Status',
        'compatibleSoftwareVersions' => 'App\\Struct\\License\\CompatibleSoftwareVersions',
        'changelogs' => 'App\\Struct\\License\\Changelogs',
        'archives' => 'App\\Struct\\License\\Archives',
    ];
}
