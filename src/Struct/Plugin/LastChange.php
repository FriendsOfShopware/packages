<?php
namespace App\Struct\Plugin;

class LastChange extends \App\Struct\Struct
{
	/** @var string */
	public $date;

	/** @var integer */
	public $timezone_type;

	/** @var string */
	public $timezone;

	public static $mappedFields = [];
}
