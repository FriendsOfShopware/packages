<?php
namespace App\Struct\License;

/**
 * @property integer $id
 * @property string $localPath
 * @property string $remoteLink
 * @property NULL $shopwareMajorVersion
 * @property boolean $ioncubeEncrypted
 */
class Archives extends Struct
{

    public $id = null;

    public $localPath = null;

    public $remoteLink = null;

    public $shopwareMajorVersion = null;

    public $ioncubeEncrypted = null;

    protected static $mappedFields = [
        
    ];


}
