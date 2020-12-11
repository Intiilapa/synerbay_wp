<?php

namespace TierPricingTable\Admin\ProductManagers;

use  TierPricingTable\PriceManager ;
use  WC_Product ;
/**
 * Class ProductManager
 *
 * @package TierPricingTable\Admin\Product
 */
class ProductManager extends ProductManagerAbstract
{
    /**
     * Register hooks
     */
    protected function hooks()
    {
        // Tiered Pricing Product Tab
        add_filter(
            'woocommerce_product_data_tabs',
            array( $this, 'registerTieredPricingProductTab' ),
            99,
            1
        );
        add_action( 'woocommerce_product_data_panels', array( $this, 'renderTieredPricingTab' ) );
        // Simple Product
        add_action( 'woocommerce_product_options_pricing', array( $this, 'renderPriceRules' ) );
        // Saving
        add_action( 'woocommerce_process_product_meta', array( $this, 'updatePriceRules' ) );
    }
    
    /**
     * Add tiered pricing tab to woocommerce product tabs
     *
     * @param array $productTabs
     *
     * @return array
     */
    public function registerTieredPricingProductTab( $productTabs )
    {
        $productTabs['tiered-pricing-tab'] = array(
            'label'  => __( 'Tiered Pricing', 'tier-pricing-table' ),
            'target' => 'tiered-pricing-data',
            'class'  => array( 'show_if_simple', 'show_if_variable' ),
        );
        return $productTabs;
    }
    
    /**
     * Render content for the tiered pricing tab
     */
    public function renderTieredPricingTab()
    {
        global  $post ;
        $min = PriceManager::getProductQtyMin( $post->ID, 'edit' );
        $desc = __( 'Set if you are selling the product from qty more than 1', 'tier-pricing-table' );
        $tip = true;
        $customAttrs = array(
            'min'  => 1,
            'step' => 1,
        );
        
        if ( $this->licence->isFree() ) {
            $desc = '<span style="color:red">Available only in the premium version</span>';
            $tip = false;
            $customAttrs['disabled'] = true;
        }
        
        ?>
        <div id="tiered-pricing-data" class="panel woocommerce_options_panel">
			<?php 
        do_action( 'tier_pricing_table/admin/pricing_tab_begin', $post->ID );
        woocommerce_wp_text_input( array(
            'id'                => '_tiered_pricing_minimum',
            'wrapper_class'     => 'show_if_simple show_if_variable',
            'type'              => 'number',
            'custom_attributes' => $customAttrs,
            'value'             => $min,
            'label'             => __( 'Minimum quantity', 'tier-pricing-table' ),
            'description'       => $desc,
            'default'           => 1,
            'desc_tip'          => $tip,
        ) );
        ?>

            <div class="hidden show_if_variable">
				<?php 
        $type = PriceManager::getPricingType( $post->ID, 'fixed', 'edit' );
        $this->fileManager->includeTemplate( 'admin/add-price-rules.php', array(
            'price_rules_fixed'      => PriceManager::getFixedPriceRules( $post->ID, 'edit' ),
            'price_rules_percentage' => PriceManager::getPercentagePriceRules( $post->ID, 'edit' ),
            'type'                   => $type,
            'prefix'                 => 'variable',
            'isFree'                 => $this->licence->isFree(),
        ) );
        ?>
            </div>

            <?php 
        do_action( 'tier_pricing_table/admin/pricing_tab_end', $post->ID );
        ?>
        </div>
		<?php 
    }
    
    /**
     * Update price quantity rules for simple product
     *
     * @param int $product_id
     */
    public function updatePriceRules( $product_id )
    {
        $data = $_POST;
        $prefix = ( isset( $data['product-type'] ) && in_array( $data['product-type'], array( 'simple', 'variable' ) ) ? sanitize_text_field( $data['product-type'] ) : 'simple' );
        $fixedAmounts = ( isset( $data['tiered_price_fixed_quantity_' . $prefix] ) ? (array) $data['tiered_price_fixed_quantity_' . $prefix] : array() );
        $fixedPrices = ( !empty($data['tiered_price_fixed_price_' . $prefix]) ? (array) $data['tiered_price_fixed_price_' . $prefix] : array() );
        PriceManager::updateFixedPriceRules( $fixedAmounts, $fixedPrices, $product_id );
    }
    
    /**
     * Render inputs for price rules on a simple product
     *
     * @global WC_Product $product_object
     */
    public function renderPriceRules()
    {
        global  $product_object ;
        
        if ( $product_object instanceof WC_Product ) {
            $type = PriceManager::getPricingType( $product_object->get_id(), 'fixed', 'edit' );
            $this->fileManager->includeTemplate( 'admin/add-price-rules.php', array(
                'price_rules_fixed'      => PriceManager::getFixedPriceRules( $product_object->get_id(), 'edit' ),
                'price_rules_percentage' => PriceManager::getPercentagePriceRules( $product_object->get_id(), 'edit' ),
                'type'                   => $type,
                'prefix'                 => 'simple',
                'isFree'                 => $this->licence->isFree(),
            ) );
        }
    
    }

}