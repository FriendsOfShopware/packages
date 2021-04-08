<?php

namespace App\Components;

use App\Components\Api\AccessToken;

class ResolverContext
{
    public function __construct(public bool $usesDeprecatedHeader, public AccessToken $token)
    {
    }
}
