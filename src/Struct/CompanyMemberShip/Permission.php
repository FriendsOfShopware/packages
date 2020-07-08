<?php declare(strict_types=1);

namespace App\Struct\CompanyMemberShip;

use App\Struct\Struct;

class Permission extends Struct
{
    public int $id;

    public string $context;

    public string $name;
}
