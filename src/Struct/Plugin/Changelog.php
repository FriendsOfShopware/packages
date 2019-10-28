<?php
namespace App\Struct\Plugin;

class Changelog extends \App\Struct\Struct
{
	/** @var string */
	public $version;

	/** @var string */
	public $text;

	/** @var LastChange */
	public $creationDate;

	public static $mappedFields = ['creationDate' => 'App\Struct\Plugin\LastChange'];
}
