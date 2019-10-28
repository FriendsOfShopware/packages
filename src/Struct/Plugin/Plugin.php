<?php
namespace App\Struct\Plugin;

class Plugin extends \App\Struct\Struct
{
	/** @var integer */
	public $id;

	/** @var string */
	public $name;

	/** @var string */
	public $code;

	/** @var boolean */
	public $useContactForm;

	/** @var LastChange */
	public $lastChange;

	/** @var boolean */
	public $support;

	/** @var boolean */
	public $supportOnlyCommercial;

	/** @var string */
	public $iconPath;

	/** @var string */
	public $examplePageUrl;

	/** @var string */
	public $moduleKey;

	/** @var LastChange */
	public $creationDate;

	/** @var string */
	public $statusComment;

	/** @var boolean */
	public $responsive;

	/** @var boolean */
	public $automaticBugfixVersionCompatibility;

	/** @var boolean */
	public $hiddenInStore;

	/** @var Producer */
	public $producer;

	/** @var PriceModels */
	public $priceModels;

	/** @var Pictures */
	public $pictures;

	/** @var Comments */
	public $comments;

	/** @var integer */
	public $ratingAverage;

	/** @var string */
	public $label;

	/** @var string */
	public $description;

	/** @var string */
	public $installationManual;

	/** @var string */
	public $version;

	/** @var Changelog */
	public $changelog;

	/** @var Addons */
	public $addons;

	/** @var string */
	public $link;

	/** @var boolean */
	public $redirectToStore;

	/** @var NULL */
	public $lowestPriceValue;

	public static $mappedFields = [
		'lastChange' => 'App\Struct\Plugin\LastChange',
		'creationDate' => 'App\Struct\Plugin\LastChange',
		'producer' => 'App\Struct\Plugin\Producer',
		'priceModels' => 'App\Struct\Plugin\PriceModels',
		'pictures' => 'App\Struct\Plugin\Pictures',
		'comments' => 'App\Struct\Plugin\Comments',
		'changelog' => 'App\Struct\Plugin\Changelog',
		'addons' => 'App\Struct\Plugin\Addons',
	];
}
