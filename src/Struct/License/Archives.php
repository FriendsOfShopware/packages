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
    public $id = null;

    public $localPath = null;

    public $remoteLink = null;

    public $shopwareMajorVersion = null;

    public $ioncubeEncrypted = null;
}
