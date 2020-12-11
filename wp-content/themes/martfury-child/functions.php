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
 * Enable custom registration details for Dokan dashboard
 *
 * @since 3.0.16
 * @package dokan
 *
 */

add_filter( 'dokan_settings_form_bottom', 'extra_fields', 10, 2);

 function extra_fields( $current_user, $profile_info ){
    $vendor_vat              = isset( $profile_info['vendor_vat'] ) ? $profile_info['vendor_vat'] : '';
    $vendor_industry         = isset( $profile_info['vendor_industry'] ) ? $profile_info['vendor_industry'] : '';
    $vendor_type             = isset( $profile_info['vendor_type'] ) ? $profile_info['vendor_type'] : '';
    $vendor_shipping_to      = isset( $profile_info['vendor_shipping_to'] ) ? $profile_info['vendor_shipping_to'] : '';
    $address_country         = isset( $store_info['address']['country'] ) ? $store_info['address']['country'] : '';
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
            <input type="text" class="dokan-form-control input-md valid" name="vendor_vat" id="reg_seller_url" value="<?php echo $vendor_vat; ?>" />
        </div>
    </div>

     <!-- Company Industry -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Industry', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <select name="vendor_industry" class="dokan-form-control" id="vendor_industry">
                 <option value="Agriculture & Food" <?php if($vendor_industry =="Agriculture & Food") echo 'selected="selected"';?>>Agriculture & Food</option>
                 <option value="Apparel,Textiles & Accessories" <?php if($vendor_industry =="Apparel,Textiles & Accessories") echo 'selected="selected"';?>>Apparel,Textiles & Accessories</option>
                 <option value="Auto & Transportation" <?php if($vendor_industry =="Auto & Transportation") echo 'selected="selected"';?>>Auto & Transportation</option>
                 <option value="Bags, Shoes & Accessories" <?php if($vendor_industry =="Bags, Shoes & Accessories") echo 'selected="selected"';?>>Bags, Shoes & Accessories</option>
                 <option value="Electronics" <?php if($vendor_industry =="Electronics") echo 'selected="selected"';?>>Electronics</option>
                 <option value="Electrical Equipment, Components & Telecoms" <?php if($vendor_industry =="Electrical Equipment, Components & Telecoms") echo 'selected="selected"';?>>Electrical Equipment, Components & Telecoms</option>
                 <option value="Gifts, Sports & Toys" <?php if($vendor_industry =="Gifts, Sports & Toys") echo 'selected="selected"';?>>Gifts, Sports & Toys</option>
                 <option value="Health & Beauty" <?php if($vendor_industry =="Health & Beauty") echo 'selected="selected"';?>>Health & Beauty</option>
                 <option value="Home, Lights & Construction" <?php if($vendor_industry =="ome, Lights & Construction") echo 'selected="selected"';?>>Home, Lights & Construction</option>
                 <option value="Machinery, Industrial Parts & Tools" <?php if($vendor_industry =="Machinery, Industrial Parts & Tools") echo 'selected="selected"';?>>Machinery, Industrial Parts & Tools</option>
                 <option value="Metallurgy, Chemicals, Rubber & Plastics" <?php if($vendor_industry =="Metallurgy, Chemicals, Rubber & Plastics") echo 'selected="selected"';?>>Metallurgy, Chemicals, Rubber & Plastics</option>
                 <option value="Packaging, Advertising & Office" <?php if($vendor_industry =="Packaging, Advertising & Office") echo 'selected="selected"';?>>Packaging, Advertising & Office</option>
             </select>
         </div>
     </div>

     <!-- Company Type -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Company Type', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <select name="vendor_type" class="dokan-form-control" id="vendor_type">
                 <option value="manufacturer" <?php if($vendor_type =="manufacturer") echo 'selected="selected"';?>>Manufacturer</option>
                 <option value="wholesaler" <?php if($vendor_type =="wholesaler") echo 'selected="selected"';?>>Wholesaler</option>
                 <option value="retailer" <?php if($vendor_type =="retailer") echo 'selected="selected"';?>>Retailer</option>
                 <option value="service" <?php if($vendor_type =="service") echo 'selected="selected"';?>>Service</option>
             </select>
         </div>
     </div>

    <?php
        $country_obj   = new WC_Countries();
        $countries     = $country_obj->countries;
        $states        = $country_obj->states;
    ?>

     <!-- Shipping to -->
     <input type="hidden" id="dokan_selected_shipping_country" value="<?php echo esc_attr( $vendor_shipping_to )?>" />
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Current Country shipping to', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <p style="text-align: left;"><?php echo $vendor_shipping_to ?></p>
         </div>
     </div>

     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Set new shipping country', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <select name="vendor_shipping_to" class="dokan-form-control" id="vendor_shipping_to">
                 <?php dokan_country_dropdown( $countries, $address_country, false ); ?>
             </select>
         </div>
     </div>

     <!-- Annual revenue -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Annual revenue', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <select name="vendor_revenue" class="dokan-form-control" id="vendor_revenue">
                 <option value="$0-$500.000" <?php if($vendor_revenue =="$0-$500.000") echo 'selected="selected"';?>>$0 - $500.000</option>
                 <option value="$500.000-$1.000.000" <?php if($vendor_revenue =="$500.000-$1.000.000") echo 'selected="selected"';?>>$500.000 - $1.000.000</option>
                 <option value="$1.000.000-$5.000.000" <?php if($vendor_revenue =="$1.000.000-$5.000.000") echo 'selected="selected"';?>>$1.000.000 - $5.000.000</option>
                 <option value="$5.000.000-$10.000.000" <?php if($vendor_revenue =="$5.000.000-$10.000.000") echo 'selected="selected"';?>>$5.000.000 - $10.000.000</option>
                 <option value="$10.000.000-$50.000.000" <?php if($vendor_revenue =="$10.000.000-$50.000.000") echo 'selected="selected"';?>>$10.000.000 - $50.000.000</option>
                 <option value="$50.000.000<" <?php if($vendor_revenue =="$50.000.000") echo 'selected="selected"';?>>$50.000.000<</option>
             </select>
         </div>
     </div>

     <!-- Employees -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Employees', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <select name="vendor_employees" class="dokan-form-control" id="vendor_employees">
                 <option value="<10 employees" <?php if($vendor_employees =="<10 employees") echo 'selected="selected"';?>><10 employees</option>
                 <option value="10-50 employees" <?php if($vendor_employees =="10-50 employees") echo 'selected="selected"';?>>10 - 50 employees</option>
                 <option value="50-100 employees" <?php if($vendor_employees =="$50-100 employees") echo 'selected="selected"';?>>50 - 100 employees</option>
                 <option value="100-500 employees" <?php if($vendor_employees =="100-500 employees") echo 'selected="selected"';?>>100 - 500 employees</option>
                 <option value="500-1000 employees" <?php if($vendor_employees =="500-1000 employees") echo 'selected="selected"';?>>500 - 1000 employees</option>
                 <option value="1000< employees" <?php if($vendor_employees =="1000< employees") echo 'selected="selected"';?>>1000< employees</option>
             </select>
         </div>
     </div>

     <!-- Product range -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Product range', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
             <select name="vendor_product_range" class="dokan-form-control" id="vendor_product_range">
                 <option value="<10 products" <?php if($vendor_product_range =="<10 products") echo 'selected="selected"';?>><10 products</option>
                 <option value="10-50 products" <?php if($vendor_product_range =="10-50 products") echo 'selected="selected"';?>>10 - 50 products</option>
                 <option value="50-100 products" <?php if($vendor_product_range =="50-100 products") echo 'selected="selected"';?>>50 - 100 products</option>
                 <option value="100-300 products" <?php if($vendor_product_range =="100-300 products") echo 'selected="selected"';?>>100 - 300 products</option>
                 <option value="300-1000 products" <?php if($vendor_product_range =="300-1000 products") echo 'selected="selected"';?>>300 - 1000 products</option>
                 <option value="1000< products" <?php if($vendor_product_range =="1000< products") echo 'selected="selected"';?>>1000< products</option>
             </select>
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

    if ( isset( $_POST['vendor_industry'] ) ) {
        $dokan_settings['vendor_industry'] = $_POST['vendor_industry'];
    }

    if ( isset( $_POST['vendor_type'] ) ) {
        $dokan_settings['vendor_type'] = $_POST['vendor_type'];
    }

    if ( isset( $_POST['vendor_shipping_to'] ) ) {
        $dokan_settings['vendor_shipping_to'] = $_POST['vendor_shipping_to'];
    }

    if ( isset( $_POST['vendor_revenue'] ) ) {
        $dokan_settings['vendor_revenue'] = $_POST['vendor_revenue'];
    }

    if ( isset( $_POST['vendor_employees'] ) ) {
        $dokan_settings['vendor_employees'] = $_POST['vendor_employees'];
    }

    if ( isset( $_POST['vendor_product_range'] ) ) {
        $dokan_settings['vendor_product_range'] = $_POST['vendor_product_range'];
    }

    update_user_meta( $store_id, 'dokan_profile_settings', $dokan_settings );
}

// Frond-end (Vendor page)
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

    <?php }

    /*
    *
    * Add offers to dashboard
    *
    * @since 3.0.16
    * @package dokan
    *
    */
    add_filter( 'dokan_query_var_filter', 'dokan_load_document_menu' );
    function dokan_load_document_menu( $query_vars ) {
        $query_vars['offers'] = 'offers';
        return $query_vars;
    }
    add_filter( 'dokan_get_dashboard_nav', 'dokan_add_offers_menu' );
    function dokan_add_offers_menu( $urls ) {
        $urls['help'] = array(
            'title' => __( 'Offers', 'dokan'),
            'icon'  => '<i class="fa fa-user"></i>',
            'url'   => dokan_get_navigation_url( 'offers' ),
            'pos'   => 51
        );
        return $urls;
    }
    add_action( 'dokan_load_custom_template', 'dokan_load_template' );
    function dokan_load_template( $query_vars ) {
        if ( isset( $query_vars['offers'] ) ) {
            require_once dirname( __FILE__ ). '/custom-dashboard/offers.php';
        }
    }

/**
 * NOTE: This code example uses the generic vendor prefix 'prefix_' and omits text domains where
 * the WordPress internationalization functions are used. You should replace 'prefix_' with your
 * own prefix and insert your text domain where appropriate when incorporating this code into your
 * plugin or theme.
 */

/**
 * Adds an 'About' tab to the Dokan settings navigation menu.
 *
 * @param array $menu_items
 *
 * @return array
 */
function sb_add_about_tab( $menu_items ) {
    $menu_items['about'] = [
        'title'      => __( 'About' ),
        'icon'       => '<i class="fa fa-user-circle"></i>',
        'url'        => dokan_get_navigation_url( 'settings/about' ),
        'pos'        => 90,
        'permission' => 'dokan_view_store_settings_menu',
    ];

    return $menu_items;
}

add_filter( 'dokan_get_dashboard_settings_nav', 'sb_add_about_tab' );

/**
 * Sets the title for the 'About' settings tab.
 *
 * @param string $title
 * @param string $tab
 *
 * @return string Title for tab with slug $tab
 */
function sb_set_about_tab_title( $title, $tab ) {
    if ( 'about' === $tab ) {
        $title = __( 'About Me' );
    }

    return $title;
}

add_filter( 'dokan_dashboard_settings_heading_title', 'sb_set_about_tab_title', 10, 2 );

/**
 * Sets the help text for the 'About' settings tab.
 *
 * @param string $help_text
 * @param string $tab
 *
 * @return string Help text for tab with slug $tab
 */
function sb_set_about_tab_help_text( $help_text, $tab ) {
    if ( 'about' === $tab ) {
        $help_text = __( 'Personalize your store page by telling customers a little about yourself.' );
    }

    return $help_text;
}

add_filter( 'dokan_dashboard_settings_helper_text', 'sb_set_about_tab_help_text', 10, 2 );

/**
 * Outputs the content for the 'About' settings tab.
 *
 * @param array $query_vars WP query vars
 */
function sb_output_help_tab_content( $query_vars ) {
    if ( isset( $query_vars['settings'] ) && 'about' === $query_vars['settings'] ) {
        if ( ! current_user_can( 'dokan_view_store_settings_menu' ) ) {
            dokan_get_template_part ('global/dokan-error', '', [
                'deleted' => false,
                'message' => __( 'You have no permission to view this page', 'dokan-lite' )
            ] );
        } else {
            $user_id        = get_current_user_id();
            $bio            = get_user_meta( $user_id, 'sb_bio', true );
            $birthdate      = get_user_meta( $user_id, 'sb_birthdate', true );
            $favorite_color = get_user_meta( $user_id, 'sb_favorite_color', true );

            ?>
            <form method="post" id="settings-form"  action="" class="dokan-form-horizontal">
                <?php wp_nonce_field( 'dokan_about_settings_nonce' ); ?>

                <div class="dokan-form-group">
                    <label class="dokan-w3 dokan-control-label" for="bio">
                        <?php esc_html_e( 'Bio' ); ?>
                    </label>
                    <div class="dokan-w5">
                        <textarea class="dokan-form-control" name="bio" id="bio" placeholder="<?php esc_attr_e( 'Tell your story' ); ?>"><?php echo esc_html( $bio ); ?></textarea>
                        <p class="help-block"><?php esc_html_e( 'Tell your customers a little about yourself.' ); ?></p>
                    </div>
                </div>

                <div class="dokan-form-group">
                    <label class="dokan-w3 dokan-control-label" for="birthdate">
                        <?php esc_html_e( 'Birthdate' ); ?>
                    </label>
                    <div class="dokan-w5">
                        <input class="dokan-form-control" type="date" name="birthdate" id="birthdate" value="<?php echo esc_attr( $birthdate ); ?>">
                    </div>
                </div>

                <div class="dokan-form-group">
                    <label class="dokan-w3 dokan-control-label" for="favorite_color">
                        <?php esc_html_e( 'Favorite Color' ); ?>
                    </label>
                    <div class="dokan-w5">
                        <select class="dokan-form-control" name="favorite_color" id="favorite_color">
                            <?php
                            $colors = [
                                ''       => __( 'Select a color' ),
                                'red'    => __( 'Red' ),
                                'orange' => __( 'Orange' ),
                                'yellow' => __( 'Yellow' ),
                                'green'  => __( 'Green' ),
                                'blue'   => __( 'Blue' ),
                                'other'  => __( 'Other' ),
                            ];

                            foreach ( $colors as $value => $label ) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    $value,
                                    selected( $value, $favorite_color, false ),
                                    $label
                                );
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="dokan-form-group">
                    <div class="dokan-w4 ajax_prev dokan-text-left" style="margin-left: 25%">
                        <input type="submit" name="dokan_update_about_settings" class="dokan-btn dokan-btn-danger dokan-btn-theme" value="<?php esc_attr_e( 'Update Settings' ); ?>">
                    </div>
                </div>
            </form>

            <style>
                #settings-form p.help-block {
                    margin-bottom: 0;
                }
            </style>
            <?php
        }
    }
}

add_action( 'dokan_render_settings_content', 'sb_output_help_tab_content' );

/**
 * Saves the settings on the 'About' tab.
 *
 * Hooked with priority 5 to run before WeDevs\Dokan\Dashboard\Templates::ajax_settings()
 */
function sb_save_about_settings() {
    $user_id   = dokan_get_current_user_id();
    $post_data = wp_unslash( $_POST );
    $nonce     = isset( $post_data['_wpnonce'] ) ? $post_data['_wpnonce'] : '';

    // Bail if another settings tab is being saved
    if ( ! wp_verify_nonce( $nonce, 'dokan_about_settings_nonce' ) ) {
        return;
    }

    $bio            = sanitize_text_field( $post_data['bio'] );
    $birthdate      = sanitize_text_field( $post_data['birthdate'] );
    $favorite_color = sanitize_text_field( $post_data['favorite_color'] );

    // Require that the user is 18 years of age or older
    $eighteen_years_ago = strtotime( '-18 years 00:00:00' );

    if ( $birthdate && strtotime( $birthdate ) > $eighteen_years_ago ) {
        wp_send_json_error( __( 'You must be at least eighteen years old - is your birthdate correct?' ) );
    }

    update_user_meta( $user_id, 'sb_bio', $bio );
    update_user_meta( $user_id, 'sb_birthdate', $birthdate );
    update_user_meta( $user_id, 'sb_favorite_color', $favorite_color );

    wp_send_json_success( array(
        'msg' => __( 'Your information has been saved successfully' ),
    ) );
}

add_action( 'wp_ajax_dokan_settings', 'sb_save_about_settings', 5 );

