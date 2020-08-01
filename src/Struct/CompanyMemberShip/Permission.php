<?php

declare(strict_types=1);

namespace App\Struct\CompanyMemberShip;

use App\Struct\Struct;

class Permission extends Struct
{
    public int $id;

    public string $context;

    public string $name;

    public static function create(string $permission): self
    {
        $me = new self();
        $me->name = $permission;
        $me->context = $permission;
        $me->id = 1;

        return $me;
    }
}
