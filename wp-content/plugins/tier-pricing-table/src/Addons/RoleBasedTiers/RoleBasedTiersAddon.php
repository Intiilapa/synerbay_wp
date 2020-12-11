<?php

namespace TierPricingTable\Addons\RoleBasedTiers;

use  TierPricingTable\Addons\AbstractAddon ;
use  TierPricingTable\PriceManager ;
use  WP_Post ;
class RoleBasedTiersAddon extends AbstractAddon
{
    const  GET_ROLE_ROW_HTML__ACTION = 'tpt_get_role_row_html' ;
    const  SETTING_ENABLE_KEY = 'enable_role_based_pricing_addon' ;
    /**
     * @return string|void
     */
    public function getName()
    {
        return __( 'Role based tiered pricing', 'tier-pricing-table' );
    }
    
    /**
     * @return bool
     */
    public function isActive()
    {
        $active = $this->settings->get( self::SETTING_ENABLE_KEY, 'yes' ) === 'yes';
        return apply_filters( 'tier_pricing_table/addons/role_based_pricing_active', $active, $this );
    }
    
    /**
     * Run
     */
    public function run()
    {
        // Get row ajax
        add_action( 'wp_ajax_' . self::GET_ROLE_ROW_HTML__ACTION, [ $this, 'getRoleRowHtml' ] );
        // Simple view
        add_action( 'tier_pricing_table/admin/pricing_tab_end', [ $this, 'renderProductPage' ] );
        // Variable
        add_action(
            'woocommerce_variation_options_pricing',
            [ $this, 'renderPriceRulesVariation' ],
            11,
            3
        );
        add_action(
            'woocommerce_variation_options',
            array( $this, 'addVariableRoleRulesOption' ),
            10,
            3
        );
        // Filter the rules
        /**
         * @priority 5
         */
        add_filter(
            'tier_pricing_table/price/product_price_rules',
            [ $this, 'addRolePricing' ],
            5,
            2
        );
    }
    
    /**
     * @param array $_rules
     * @param int $productId
     *
     * @return array
     */
    public function addRolePricing( $_rules, $productId )
    {
        $userRoles = $this->getCurrentUserRoles();
        if ( !empty($userRoles) ) {
            foreach ( $userRoles as $role ) {
                
                if ( RoleBasedPriceManager::roleHasRules( $role, $productId ) ) {
                    $rules = RoleBasedPriceManager::getPriceRules( $productId, $role );
                    $rulesType = RoleBasedPriceManager::getPricingType( $productId, $role );
                    $rulesMinimum = RoleBasedPriceManager::getProductQtyMin( $productId, $role );
                    add_filter( 'tier_pricing_table/price/minimum', function () use( $rulesMinimum ) {
                        return $rulesMinimum;
                    }, 10 );
                    add_filter( 'tier_pricing_table/price/type', function () use( $rulesType ) {
                        return $rulesType;
                    }, 10 );
                    return $rules;
                }
            
            }
        }
        return $_rules;
    }
    
    protected function getCurrentUserRoles()
    {
        $roles = [];
        $user = wp_get_current_user();
        if ( $user ) {
            $roles = (array) $user->roles;
        }
        return apply_filters( 'tier_pricing_table/role_based_rules/current_user_roles', $roles, get_current_user_id() );
    }
    
    /**
     * @param int $loop
     * @param array $variation_data
     * @param WP_Post $variation
     */
    public function addVariableRoleRulesOption( $loop, $variation_data, $variation )
    {
        $checked = get_post_meta( $variation->ID, '_variable_roles_tiered_pricing', true ) === 'yes';
        ?>
        <label class="tips"
               data-tip="<?php 
        esc_attr_e( 'Enable this option to enable stock management at variation level', 'woocommerce' );
        ?>">
			<?php 
        esc_html_e( 'Tiered pricing for roles', 'woocommerce' );
        ?>
            <input type="checkbox" class="checkbox variable_roles_tiered_pricing"
                   name="variable_roles_tiered_pricing[<?php 
        echo  esc_attr( $loop ) ;
        ?>]" <?php 
        checked( $checked );
        ?> />
        </label>
		<?php 
    }
    
    /**
     * AJAX Handler
     */
    public function getRoleRowHtml()
    {
        $nonce = ( isset( $_GET['nonce'] ) ? $_GET['nonce'] : false );
        
        if ( wp_verify_nonce( $nonce, self::GET_ROLE_ROW_HTML__ACTION ) ) {
            $role = ( isset( $_GET['role'] ) ? $_GET['role'] : false );
            $product_id = ( isset( $_GET['product_id'] ) ? $_GET['product_id'] : 0 );
            $loop = ( isset( $_GET['loop'] ) ? $_GET['loop'] : 0 );
            $role = get_role( $role );
            $product = wc_get_product( $product_id );
            
            if ( $role && $product ) {
                $type = ( $product->is_type( 'variation' ) ? 'variation' : 'simple' );
                wp_send_json( [
                    'success'       => true,
                    'role_row_html' => $this->fileManager->renderTemplate( "addons/role-based-tiers/{$type}/role.php", [
                    'role'                   => $role->name,
                    'loop'                   => $loop,
                    'fileManager'            => $this->fileManager,
                    'minimum_amount'         => RoleBasedPriceManager::getProductQtyMin( $product_id, $role->name, 'edit' ),
                    'price_rules_fixed'      => RoleBasedPriceManager::getFixedPriceRules( $product_id, $role->name, 'edit' ),
                    'price_rules_percentage' => RoleBasedPriceManager::getPercentagePriceRules( $product_id, $role->name, 'edit' ),
                    'type'                   => RoleBasedPriceManager::getPricingType(
                    $product_id,
                    $role->name,
                    'fixed',
                    'edit'
                ),
                ] ),
                ] );
            }
            
            wp_send_json( [
                'success'       => false,
                'error_message' => __( 'Invalid role', 'tier-pricing-table' ),
            ] );
        }
        
        wp_send_json( [
            'success'       => false,
            'error_message' => __( 'Invalid nonce', 'tier-pricing-table' ),
        ] );
    }
    
    /**
     *
     */
    public function renderProductPage()
    {
        global  $post ;
        $this->fileManager->includeTemplate( 'addons/role-based-tiers/simple/role-based-block.php', [
            'fileManager' => $this->fileManager,
            'product_id'  => $post->ID,
            'is_premium'  => tpt_fs()->is_premium(),
        ] );
    }
    
    /**
     * @param $loop
     * @param $variation_data
     * @param $variation
     */
    public function renderPriceRulesVariation( $loop, $variation_data, $variation )
    {
        $this->fileManager->includeTemplate( 'addons/role-based-tiers/variation/role-based-block.php', [
            'fileManager' => $this->fileManager,
            'product_id'  => $variation->ID,
            'loop'        => $loop,
            'is_premium'  => tpt_fs()->is_premium(),
        ] );
    }

}