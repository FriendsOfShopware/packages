<?php

namespace App\Struct\Shop;

class Module extends \App\Struct\Struct
{
    public int $id;

    public string $name;

    public string $description;

    public int $price;

    public int $priceMonthlyPayment;

    public int $price24;

    public int $price24MonthlyPayment;

    public int $upgradeOrder;

    public int $durationInMonths;

    public string $bookingKey;

    public static $mappedFields = [];
}
