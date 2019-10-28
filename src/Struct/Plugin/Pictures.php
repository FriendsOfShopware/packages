<?php
namespace App\Struct\Plugin;

class Pictures extends \App\Struct\Struct
{
	/** @var string */
	public $remoteLink;

	/** @var boolean */
	public $preview;

	/** @var integer */
	public $priority;

	/** @var integer */
	public $id;

	public static $mappedFields = [];
}
