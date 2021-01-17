<?php

declare(strict_types=1);

namespace App\Struct\CompanyMemberShip;

use App\Struct\Struct;

class CompanyMemberShip extends Struct
{
    public const PERMISSION_TO_ACCESS_SHOPS = [
        self::COMPANY_SHOPS_PERMISSION,
        self::PARTNER_SHOPS_PERMISSION,
        self::WILDCARD_SHOP_PERMISSION,
    ];

    public const COMPANY_SHOPS_PERMISSION = 'shopOwner_shops';
    public const PARTNER_SHOPS_PERMISSION = 'partner_accounting';
    public const WILDCARD_SHOP_PERMISSION = 'partner_wildcard';

    public static array $mappedFields = ['company' => Company::class, 'roles' => Role::class];

    public int $id;

    public bool $active;

    public Company $company;

    /**
     * @var Role[]
     */
    public array $roles;

    public function can(string ...$name): bool
    {
        $required = \count($name);
        $found = [];

        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                if (\in_array($permission->name, $name, true)) {
                    $found[$permission->name] = true;
                }
            }
        }

        return \count($found) === $required;
    }

    public function canOneOf(string ...$name): bool
    {
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                if (\in_array($permission->name, $name, true)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function canAccessShop(): bool
    {
        return $this->canOneOf(...self::PERMISSION_TO_ACCESS_SHOPS);
    }
}
