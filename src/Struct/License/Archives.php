<?php

namespace App\Struct\License;

use App\Struct\Struct;

class Archives extends Struct
{
    public ?int $id;

    public ?string $remoteLink;

    public mixed $shopwareMajorVersion;

    public ?bool $ioncubeEncrypted;
}
