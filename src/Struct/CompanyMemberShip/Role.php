<?php declare(strict_types=1);

namespace App\Struct\CompanyMemberShip;

use App\Struct\Struct;

class Role extends Struct
{
    public static $mappedFields = [
        'permissions' => Permission::class,
    ];

    public int $id;

    public string $name;

    /**
     * @var Permission[]
     */
    public array $permissions;
}
