<?php namespace TierPricingTable\Addons;

use TierPricingTable\Addons\CategoryTiers\CategoryTierAddon;
use TierPricingTable\Addons\ManualOrders\ManualOrdersAddon;
use TierPricingTable\Addons\MinQuantity\MinQuantity;
use Premmerce\SDK\V2\FileManager\FileManager;
use TierPricingTable\Addons\RoleBasedTiers\RoleBasedTiersAddon;
use TierPricingTable\Settings\Settings;

class Addons {

	/**
	 * @var AbstractAddon[]
	 */
	private $addons;

	/**
	 * @var FileManager
	 */
	private $fileManager;

	/**
	 * @var Settings
	 */
	private $settings;

	public function __construct( FileManager $fileManger, Settings $settings ) {
		$this->fileManager = $fileManger;
		$this->settings    = $settings;

		$this->init();
	}

	public function init() {

		$this->addons = apply_filters( 'tier_pricing_table/addons/list', array(
			CategoryTierAddon::class   => new CategoryTierAddon( $this->fileManager, $this->settings ),
			RoleBasedTiersAddon::class => new RoleBasedTiersAddon( $this->fileManager, $this->settings ),
			MinQuantity::class         => new MinQuantity( $this->fileManager, $this->settings ),
			ManualOrdersAddon::class   => new ManualOrdersAddon( $this->fileManager, $this->settings ),
		) );

		foreach ( $this->addons as $addon ) {
			if ( $addon->isActive() ) {
				$addon->run();
			}
		}
	}
}
