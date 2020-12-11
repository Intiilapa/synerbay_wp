<?php

namespace TierPricingTable\Addons\CategoryTiers;

use  TierPricingTable\Addons\AbstractAddon ;
use  TierPricingTable\PriceManager ;
use  WC_Product_Variation ;
use  WP_Term ;
class CategoryTierAddon extends AbstractAddon
{
    const  SKIP_FOR_PRODUCT_META_KEY = '_skip_category_tier_rules' ;
    const  SETTING_ENABLE_KEY = 'enable_category_addon' ;
    public function getName()
    {
        return __( 'Category tier pricing add-on', 'tier-pricing-table' );
    }
    
    public function isActive()
    {
        $active = $this->settings->get( self::SETTING_ENABLE_KEY, 'yes' ) === 'yes';
        return apply_filters( 'tier_pricing_table/addons/category_tier_pricing_active', $active, $this );
    }
    
    public function run()
    {
        // Saving
        add_action(
            'edit_term',
            [ $this, 'saveTermFields' ],
            10,
            1
        );
        add_action(
            'create_product_cat',
            [ $this, 'saveTermFields' ],
            10,
            1
        );
        add_action( 'product_cat_edit_form_fields', [ $this, 'renderEditFields' ], 99 );
        add_action( 'product_cat_add_form_fields', [ $this, 'renderAddFields' ], 99 );
        /**
         * @priority 10
         */
        add_filter(
            'tier_pricing_table/price/product_price_rules',
            [ $this, 'addCategoryPricing' ],
            10,
            4
        );
        add_action( 'tier_pricing_table/admin/pricing_tab_begin', [ $this, 'renderProductCheckbox' ] );
    }
    
    public function renderProductCheckbox( $productId )
    {
        $customAttrs = [];
        $descPostfix = '';
        
        if ( !tpt_fs()->is_premium() ) {
            $descPostfix = '<span style="color:red">Available only in the premium version</span>';
            $customAttrs['disabled'] = true;
        }
        
        woocommerce_wp_checkbox( [
            'id'                => self::SKIP_FOR_PRODUCT_META_KEY,
            'wrapper_class'     => 'show_if_simple show_if_variable',
            'type'              => 'number',
            'custom_attributes' => $customAttrs,
            'checked'           => $this->isSkipForProduct( $productId, 'edit' ),
            'label'             => __( 'Skip category rules', 'tier-pricing-table' ),
            'description'       => __( 'Don\'t take into account tiered pricing rules from categories. ' . $descPostfix, 'tier-pricing-table' ),
        ] );
    }
    
    /**
     * @param int $productId
     * @param string $context
     *
     * @return bool|mixed|void
     */
    public function isSkipForProduct( $productId, $context = 'view' )
    {
        $product = wc_get_product( $productId );
        
        if ( $product ) {
            if ( $product instanceof WC_Product_Variation ) {
                $productId = $product->get_parent_id();
            }
            $skip = get_post_meta( $productId, self::SKIP_FOR_PRODUCT_META_KEY, true ) === 'yes';
            if ( $context != 'edit' ) {
                return apply_filters(
                    'tier_pricing_table/addons/category_tier_pricing_skip_category',
                    $skip,
                    $productId,
                    $product
                );
            }
            return $skip;
        }
        
        return false;
    }
    
    /**
     * @param array $_rules
     * @param int $productId
     * @param string $type
     * @param int $parentId
     *
     * @return array
     */
    public function addCategoryPricing(
        $_rules,
        $productId,
        $type,
        $parentId
    )
    {
        $_rules = PriceManager::getPriceRules( $parentId, false, 'edit' );
        
        if ( empty($_rules) && !$this->isSkipForProduct( $parentId ) ) {
            $product = wc_get_product( $productId );
            foreach ( $product->get_category_ids() as $category_id ) {
                $rules = $this->getForTerm( $category_id );
                
                if ( $rules ) {
                    add_filter(
                        'tier_pricing_table/price/type',
                        function ( $type, $product_id ) use( $productId, $product ) {
                        $_product = wc_get_product( $product_id );
                        if ( $_product instanceof \WC_Product_Variation && $product instanceof \WC_Product_Variable ) {
                            if ( $_product->get_parent_id() == $product->get_id() ) {
                                return 'percentage';
                            }
                        }
                        if ( $product instanceof \WC_Product_Variation && $_product instanceof \WC_Product_Variable ) {
                            if ( $product->get_parent_id() == $_product->get_id() ) {
                                return 'percentage';
                            }
                        }
                        if ( $product_id == $productId ) {
                            return 'percentage';
                        }
                        return $type;
                    },
                        10,
                        2
                    );
                    return $rules;
                }
            
            }
        }
        
        return $_rules;
    }
    
    /**
     * Save metadata to custom attributes terms
     *
     * @param int $term_id
     */
    public function saveTermFields( $term_id )
    {
        $data = $_POST;
        $prefix = 'category';
        $percentageAmounts = ( isset( $data['tiered_price_percent_quantity_' . $prefix] ) ? (array) $data['tiered_price_percent_quantity_' . $prefix] : array() );
        $percentagePrices = ( !empty($data['tiered_price_percent_discount_' . $prefix]) ? (array) $data['tiered_price_percent_discount_' . $prefix] : array() );
    }
    
    /**
     * Render fields on category edit page
     *
     * @param WP_Term $category
     */
    public function renderEditFields( WP_Term $category )
    {
        
        if ( tpt_fs()->is_premium() ) {
            $rules = $this->getForTerm( $category->term_id );
            $this->fileManager->includeTemplate( 'addons/category-tiers/edit.php', [
                'rules' => $rules,
            ] );
        } else {
            $this->fileManager->includeTemplate( 'addons/category-tiers/edit-free.php' );
        }
    
    }
    
    /**
     * Render fields on category adding page
     */
    public function renderAddFields()
    {
        
        if ( tpt_fs()->is_premium() ) {
            $this->fileManager->includeTemplate( 'addons/category-tiers/add.php', [
                'rules' => [],
            ] );
        } else {
            $this->fileManager->includeTemplate( 'addons/category-tiers/add-free.php' );
        }
    
    }
    
    /**
     * @param int $term_id
     *
     * @return array
     */
    public function getForTerm( $term_id )
    {
        $rules = get_term_meta( $term_id, '_percentage_price_rules', true );
        $rules = ( !empty($rules) ? $rules : array() );
        ksort( $rules );
        return $rules;
    }

}