<?php

namespace App\Struct\License;

class PriceModel extends \App\Struct\Struct
{
    /** @var null */
    public $id;

    /** @var string */
    public $duration;

    /** @var string */
    public $type;

    /** @var string */
    public $bookingKey;

    /** @var string */
    public $bookingText;

    public static $mappedFields = [];
}
