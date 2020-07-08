<?php declare(strict_types=1);

namespace App\Struct\CompanyMemberShip;

use App\Struct\Struct;

class CompanyMemberShip extends Struct
{
    public static $mappedFields = ['company' => Company::class];

    public int $id;
    public bool $active;
    public Company $company;
}
