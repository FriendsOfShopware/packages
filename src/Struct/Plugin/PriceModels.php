<?php
namespace App\Struct\Plugin;

class PriceModels extends \App\Struct\Struct
{
	/** @var integer */
	public $id;

	/** @var string */
	public $bookingKey;

	/** @var string */
	public $bookingText;

	/** @var NULL */
	public $price;

	/** @var boolean */
	public $subscription;

	/** @var integer */
	public $discount;

	/** @var NULL */
	public $duration;

	/** @var string */
	public $discr;

	public static $mappedFields = [];
}
