<?php

namespace App\Struct\License;

use App\Struct\Struct;

/**
 * @property int    $id
 * @property string $localPath
 * @property string $remoteLink
 * @property null   $shopwareMajorVersion
 * @property bool   $ioncubeEncrypted
 */
class Archives extends Struct
{
    public $id;

    public $localPath;

    public $remoteLink;

    public $shopwareMajorVersion;

    public $ioncubeEncrypted;
}
