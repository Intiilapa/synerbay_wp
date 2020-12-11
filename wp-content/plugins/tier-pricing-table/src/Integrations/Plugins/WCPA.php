<?php namespace TierPricingTable\Integrations\Plugins;

class WCPA {

	public function __construct() {
		add_filter( 'tier_pricing_table/cart/product_cart_price', array( $this, 'addAddonsPriceToItem' ), 20, 2 );
		add_filter( 'tier_pricing_table/cart/product_cart_regular_price/item', array( $this, 'addAddonsPriceToItem' ), 20, 2 );
		add_filter( 'tier_pricing_table/cart/product_cart_price/item', array( $this, 'addAddonsPriceToItem' ), 20, 2 );
	}

	public function addAddonsPriceToItem( $price, $cart_item ) {
		if ( ! empty( $cart_item['wcpa_options_price_start'] ) ) {
			$price += $cart_item['wcpa_options_price_start'];
		}

		return $price;
	}
}
