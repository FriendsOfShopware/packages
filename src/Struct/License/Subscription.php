<?php
namespace App\Struct\License;

/**
 * @property integer $id
 * @property string $creationDate
 * @property string $expirationDate
 */
class Subscription extends Struct
{

    public $id = null;

    public $creationDate = null;

    public $expirationDate = null;

    protected static $mappedFields = [
        
    ];


}
