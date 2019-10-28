<?php
namespace App\Struct\Plugin;

class Comments extends \App\Struct\Struct
{
	/** @var string */
	public $authorName;

	/** @var string */
	public $text;

	/** @var string */
	public $headline;

	/** @var LastChange */
	public $creationDate;

	/** @var integer */
	public $rating;

	public static $mappedFields = ['creationDate' => 'App\Struct\Plugin\LastChange'];
}
