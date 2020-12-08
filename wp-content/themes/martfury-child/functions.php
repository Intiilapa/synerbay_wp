<?php
add_action( 'wp_enqueue_scripts', 'martfury_child_enqueue_scripts', 20 );
function martfury_child_enqueue_scripts() {
    wp_enqueue_style( 'martfury-child-style', get_stylesheet_uri() );
    wp_enqueue_style('child-style-custom', get_stylesheet_directory_uri() . '/custom.css');
	if ( is_rtl() ) {
		wp_enqueue_style( 'martfury-rtl', get_template_directory_uri() . '/rtl.css', array(), '20180105' );
	}
}

/*
 *
 * Enable custom details for Dokan dashboard for vendors
 *
 * @package dokan
 *
 */

add_filter( 'dokan_settings_form_bottom', 'extra_fields', 10, 2);

 function extra_fields( $current_user, $profile_info ){
    $vendor_vat              = isset( $profile_info['vendor_vat'] ) ? $profile_info['vendor_vat'] : '';
    $vendor_industry         = isset( $profile_info['vendor_industry'] ) ? $profile_info['vendor_industry'] : '';
    $vendor_type             = isset( $profile_info['vendor_type'] ) ? $profile_info['vendor_type'] : '';
    $vendor_shipping_to      = isset( $profile_info['vendor_shipping_to'] ) ? $profile_info['vendor_shipping_to'] : '';
    $vendor_revenue          = isset( $profile_info['vendor_revenue'] ) ? $profile_info['vendor_revenue'] : '';
    $vendor_employees        = isset( $profile_info['vendor_employees'] ) ? $profile_info['vendor_employees'] : '';
    $vendor_product_range    = isset( $profile_info['vendor_product_range'] ) ? $profile_info['vendor_product_range'] : '';
    ?>
    <hr>
    <!-- VAT -->
    <div class="custom dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="setting_address">
            <?php _e( 'VAT', 'dokan' ); ?>
        </label>
        <div class="dokan-w5">
            <p style="text-align: left;"><?php echo $vendor_vat ?></p>
        </div>
    </div>

     <!-- Company Industry -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Industry', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <p style="text-align: left; text-transform: capitalize;"><?php echo $vendor_industry ?></p>
         </div>
     </div>

     <!-- Company Type -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Company Type', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <p style="text-align: left; text-transform: capitalize;"><?php echo $vendor_type ?></p>
         </div>
     </div>

     <!-- Shipping to -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Shipping to', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <p style="text-align: left;"><?php echo $vendor_shipping_to ?></p>
         </div>
     </div>

     <!-- Annual revenue -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Annual revenue', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <p style="text-align: left;"><?php echo $vendor_revenue ?></p>
         </div>
     </div>

     <!-- Employees -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Employees', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <p style="text-align: left;"><?php echo $vendor_employees ?></p>
         </div>
     </div>

     <!-- Product range -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Product range', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <p style="text-align: left;"><?php echo $vendor_product_range ?></p>
         </div>
     </div>
     <hr>
    <?php
}

//Back-end
add_action( 'dokan_store_profile_saved', 'save_extra_fields', 15 );

function save_extra_fields( $store_id ) {
    $dokan_settings = dokan_get_store_info($store_id);

    if ( isset( $_POST['vendor_vat'] ) ) {
        $dokan_settings['vendor_vat'] = $_POST['vendor_vat'];
    }

    if ( isset( $_POST['vendor_type'] ) ) {
        $dokan_settings['vendor_type'] = $_POST['vendor_type'];
    }

    update_user_meta( $store_id, 'dokan_profile_settings', $dokan_settings );
}

// Frond-end
add_action( 'dokan_store_header_info_fields', 'save_seller_url', 10);

function save_seller_url($store_user){
    $store_info    = dokan_get_store_info( $store_user); ?>
<!--
    <?php if ( isset( $store_info['vendor_vat'] ) && !empty( $store_info['vendor_vat'] ) ) { ?>
        <i class="fa fa-globe"></i>
        <a href="<?php echo esc_html( $store_info['vendor_vat'] ); ?>"><?php echo esc_html( $store_info['vendor_vat'] ); ?></a>
    <?php } ?>

    <?php if ( isset( $store_info['vendor_type'] ) && !empty( $store_info['vendor_type'] ) ) { ?>
        <i class="fa fa-globe"></i>
        <a href="<?php echo esc_html( $store_info['vendor_type'] ); ?>"><?php echo esc_html( $store_info['vendor_type'] ); ?></a>
    <?php } ?>
-->
    <!-- END -->
    <?php }
    
    add_filter( 'dokan_query_var_filter', 'dokan_load_document_menu' );
    function dokan_load_document_menu( $query_vars ) {
        $query_vars['Offers'] = 'offers';
        return $query_vars;
    }

    add_filter( 'dokan_get_dashboard_nav', 'dokan_add_offers_menu' );
    function dokan_add_offers_menu( $urls ) {
        $urls['offers'] = array(
            'title' => __( 'Offers', 'dokan'),
            'icon'  => '<i class="fa fa-user"></i>',
            'url'   => dokan_get_navigation_url( 'offers' ),
            'pos'   => 51
        );
        return $urls;
    }

    add_action( 'dokan_load_custom_template', 'dokan_load_template' );
    function dokan_load_template( $query_vars ) {
        if ( isset( $query_vars['Offers'] ) ) {
            require_once dirname( __FILE__ ). '/offers.php';
        }
    }
