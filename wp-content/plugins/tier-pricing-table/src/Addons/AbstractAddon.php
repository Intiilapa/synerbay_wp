<?php namespace TierPricingTable\Addons;

use Premmerce\SDK\V2\FileManager\FileManager;
use TierPricingTable\Settings\Settings;

abstract class AbstractAddon {

	/**
	 * @var FileManager
	 */
	protected $fileManager;

	/**
	 * @var Settings
	 */
	protected $settings;

	public function __construct( FileManager $fileManager, Settings $settings ) {
		$this->fileManager = $fileManager;
		$this->settings = $settings;
	}

	abstract public function getName();

	abstract public function isActive();

	abstract public function run();
}