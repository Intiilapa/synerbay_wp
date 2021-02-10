<?php

use SynerBay\Helper\SynerBayDataHelper;

add_action('wp_enqueue_scripts', 'martfury_child_enqueue_scripts', 20);
function martfury_child_enqueue_scripts()
{
    wp_enqueue_style('martfury-child-style', get_stylesheet_uri());
    wp_enqueue_style('child-style-custom', get_stylesheet_directory_uri() . '/custom.css');
    if (is_rtl()) {
        wp_enqueue_style('martfury-rtl', get_template_directory_uri() . '/rtl.css', array(), '20180105');
    }

}
//Remove access to wp-admin
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

/*
* Overwrite current Martfury frontend/woocommerce.php
*/
function myThemeIncludes()
{
    require_once __DIR__ . '/inc/frontend/woocommerce.php';
    require_once __DIR__ . '/inc/frontend/wizard.php';

    new Martfury_Child_WooCommerce();
    new Dokan_Setup_Wizard_Override();
}

add_action('before_setup_theme', 'myThemeIncludes');

// end include parent classes
add_action('wp_footer', 'custom_footer_actions');
function custom_footer_actions()
{
    do_action('synerbay_loader');
    do_action('synerbay_loginModal');
}

/*
* Register new Sidebar
* Offer search results
*/
function synerbay_offers_sidebar()
{
    register_sidebar(
        array(
            'name' => __('Synerbay Sidebar', 'your-theme-domain'),
            'id' => 'synerbay_sidebar',
            'description' => __('Sidebar for Synerbay Offer results page', 'martfury'),
            'before_widget' => '<div class="widget-content">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
            'class' => 'catalog-sidebar'
        )
    );
}

add_action('widgets_init', 'synerbay_offers_sidebar');

/*
*
* Enable custom registration details for Dokan dashboard
*
* @since 3.0.16
* @package dokan
*
*/
add_filter('dokan_settings_form_bottom', 'extra_fields', 10, 2);

function extra_fields($current_user, $profile_info)
{
    $vendor_vat = isset($profile_info['vendor_vat']) ? $profile_info['vendor_vat'] : '';
    $vendor_industry = isset($profile_info['vendor_industry']) ? $profile_info['vendor_industry'] : '';
    $vendor_type = isset($profile_info['vendor_type']) ? $profile_info['vendor_type'] : '';
    $vendor_shipping_to = isset($profile_info['vendor_shipping_to']) ? $profile_info['vendor_shipping_to'] : '';
    $address_country = isset($store_info['address']['country']) ? $store_info['address']['country'] : '';
    $vendor_revenue = isset($profile_info['vendor_revenue']) ? $profile_info['vendor_revenue'] : '';
    $vendor_employees = isset($profile_info['vendor_employees']) ? $profile_info['vendor_employees'] : '';
    $vendor_product_range = isset($profile_info['vendor_product_range']) ? $profile_info['vendor_product_range'] : '';

    ?>

    <!-- VAT -->
    <div class="custom dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="setting_address">
            <?php _e('VAT', 'dokan'); ?>
        </label>
        <div class="dokan-w5">
            <input type="text" class="dokan-form-control input-md valid" name="vendor_vat" id="reg_seller_url"
                   value="<?php echo $vendor_vat; ?>"/>
        </div>
    </div>
    <!-- Company industry -->
    <div class="custom dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="setting_address">
            <?php _e('Industry', 'dokan'); ?>
        </label>

        <div class="dokan-w5">
            <select class="dokan-form-control" name="vendor_industry" id="vendor_industry">
                <?php
                $industries = [
                    '' => __('Select industry'),
                    'Agriculture & Food' => ('Agriculture & Food'),
                    'Apparel,Textiles & Accessories' => ('Apparel, Textiles & Accessories'),
                    'Auto & Transportation' => ('Auto & Transportation'),
                    'Bags, Shoes & Accessories' => ('Bags, Shoes & Accessories'),
                    'Electronics' => ('Electronics'),
                    'Electrical Equipment, Components & Telecoms' => ('Electrical Equipment, Components & Telecoms'),
                    'Gifts, Sports & Toys' => ('Gifts, Sports & Toys'),
                    'Health & Beauty' => ('Health & Beauty'),
                    'Home, Lights & Construction' => ('Home, Lights & Construction'),
                    'Machinery, Industrial Parts & Tools' => ('Machinery, Industrial Parts & Tools'),
                    'Metallurgy, Chemicals, Rubber & Plastics' => ('Metallurgy, Chemicals, Rubber & Plastics'),
                    'Packaging, Advertising & Office' => ('Packaging, Advertising & Office'),
                ];

                foreach ($industries as $value => $label) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        $value,
                        selected($value, $vendor_industry, false),
                        $label
                    );
                }
                ?>
            </select>
        </div>
    </div>
    <!-- Company type -->
    <div class="custom dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="setting_address">
            <?php _e('Company Type', 'dokan'); ?>
        </label>

        <div class="dokan-w5">
            <select class="dokan-form-control" name="vendor_type" id="vendor_type">
                <?php
                $company_types = [
                    '' => __('Select company type'),
                    'manufacturer' => ('Manufacturer'),
                    'wholesaler' => ('Wholesaler'),
                    'retailer' => ('Retailer'),
                    'service' => ('Service'),
                ];

                foreach ($company_types as $value => $label) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        $value,
                        selected($value, $vendor_type, false),
                        $label
                    );
                }
                ?>
            </select>
        </div>
    </div>
    <!-- Shipping to -->
    <div class="custom dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="setting_address">
            <?php _e('Shipping country', 'dokan'); ?>
        </label>
        <div class="dokan-w5">
            <select class="dokan-form-control" name="vendor_shipping_to" id="vendor_shipping_to">
                <?php
                $countries_shipping = SynerBayDataHelper::getDeliveryDestinations();

                foreach ($countries_shipping as $value => $label) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        $value,
                        selected($value, $vendor_shipping_to, false),
                        $label
                    );
                }
                ?>
            </select>
        </div>
    </div>
    <!-- Annual revenue -->
    <div class="custom dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="setting_address">
            <?php _e('Annual revenue', 'dokan'); ?>
        </label>

        <div class="dokan-w5">
            <select class="dokan-form-control" name="vendor_revenue" id="vendor_revenue">
                <?php
                $revenues = [
                    '' => __('Select annual revenue'),
                    '$0-$500.000' => ('$0 - $500.000'),
                    '$500.000-$1.000.000' => ('$500.000 - $1.000.000'),
                    '$1.000.000-$5.000.000' => ('$1.000.000 - $5.000.000'),
                    '$5.000.000-$10.000.000' => ('$5.000.000 - $10.000.000'),
                    '$10.000.000-$50.000.000' => ('$10.000.000 - $50.000.000'),
                    '$50.000.000<' => ('$50.000.000<'),

                ];

                foreach ($revenues as $value => $label) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        $value,
                        selected($value, $vendor_revenue, false),
                        $label
                    );
                }
                ?>
            </select>
        </div>
    </div>
    <!-- Employees -->
    <div class="custom dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="setting_address">
            <?php _e('Employees', 'dokan'); ?>
        </label>

        <div class="dokan-w5">
            <select class="dokan-form-control" name="vendor_employees" id="vendor_employees">
                <?php
                $employees = [
                    '' => __('Select employees count'),
                    '<10 employees' => ('< 10 employees'),
                    '10-50 employees' => ('10 - 50 employees'),
                    '50-100 employees' => ('50 - 100 employees'),
                    '100-500 employees' => ('100 - 500 employees'),
                    '500-1000 employees' => ('500 - 1000 employees'),
                    '1000< employees' => ('1000 < employees'),
                ];

                foreach ($employees as $value => $label) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        $value,
                        selected($value, $vendor_employees, false),
                        $label
                    );
                }
                ?>
            </select>
        </div>
    </div>
    <!-- Product range -->
    <div class="custom dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="setting_address">
            <?php _e('Product range', 'dokan'); ?>
        </label>

        <div class="dokan-w5">
            <select class="dokan-form-control" name="vendor_product_range" id="vendor_product_range">
                <?php
                $product_ranges = [
                    '' => __('Select a product range'),
                    '<10 products' => ('< 10 products'),
                    '10-50 products' => ('10 - 50 products'),
                    '50-100 products' => ('50 - 100 products'),
                    '100-300 products' => ('100 - 300 products'),
                    '300-1000 products' => ('300 - 1000 products'),
                    '1000< products' => ('1000 < products'),
                ];

                foreach ($product_ranges as $value => $label) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        $value,
                        selected($value, $vendor_product_range, false),
                        $label
                    );
                }
                ?>
            </select>
        </div>
    </div>

    <hr>
    <?php
}

//Back-end
add_action('dokan_store_profile_saved', 'save_extra_fields', 15);
function save_extra_fields($store_id)
{
    $dokan_settings = dokan_get_store_info($store_id);
    if (isset($_POST['vendor_vat'])) {
        $dokan_settings['vendor_vat'] = $_POST['vendor_vat'];
    }
    if (isset($_POST['vendor_industry'])) {
        $dokan_settings['vendor_industry'] = $_POST['vendor_industry'];
    }
    if (isset($_POST['vendor_type'])) {
        $dokan_settings['vendor_type'] = $_POST['vendor_type'];
    }
    if (isset($_POST['vendor_shipping_to'])) {
        $dokan_settings['vendor_shipping_to'] = $_POST['vendor_shipping_to'];
    }
    if (isset($_POST['vendor_revenue'])) {
        $dokan_settings['vendor_revenue'] = $_POST['vendor_revenue'];
    }
    if (isset($_POST['vendor_employees'])) {
        $dokan_settings['vendor_employees'] = $_POST['vendor_employees'];
    }
    if (isset($_POST['vendor_product_range'])) {
        $dokan_settings['vendor_product_range'] = $_POST['vendor_product_range'];
    }
    update_user_meta($store_id, 'dokan_profile_settings', $dokan_settings);
}

//Show on Store page
add_action('dokan_store_header_info_fields', 'save_seller_url', 10);

function save_seller_url($store_user)
{
    $store_info = dokan_get_store_info($store_user); ?>
    <?php if (isset($store_info['vendor_vat']) && !empty($store_info['vendor_vat'])) { ?>
    <li>
        <i class="fa fa-legal"></i>
        <a href="<?php echo esc_html($store_info['vendor_vat']); ?>"><?php echo esc_html($store_info['vendor_vat']); ?></a>
    </li>
<?php } ?>

    <?php if (isset($store_info['vendor_type']) && !empty($store_info['vendor_type'])) { ?>
    <li>
        <i class="fa fa-globe"></i>
        <a style="text-transform: capitalize"
           href="<?php echo esc_html($store_info['vendor_type']); ?>"><?php echo esc_html($store_info['vendor_type']); ?></a>
    </li>
<?php } ?>

    <?php if (isset($store_info['vendor_industry']) && !empty($store_info['vendor_industry'])) { ?>
    <li>
        <i class="fa fa-industry"></i>
        <a style="text-transform: capitalize"
           href="<?php echo esc_html($store_info['vendor_industry']); ?>"><?php echo esc_html($store_info['vendor_industry']); ?></a>
    </li>
<?php } ?>

    <?php if (isset($store_info['vendor_shipping_to']) && !empty($store_info['vendor_shipping_to'])) { ?>
    <li>
        <i class="fa fa-map-o"></i>
        <a style="text-transform: capitalize"
           href="<?php echo esc_html($store_info['vendor_shipping_to']); ?>"><?php echo esc_html($store_info['vendor_shipping_to']); ?></a>
    </li>
<?php } ?>

    <?php if (isset($store_info['vendor_revenue']) && !empty($store_info['vendor_revenue'])) { ?>
    <li>
        <i class="fa fa-money"></i>
        <a style="text-transform: capitalize"
           href="<?php echo esc_html($store_info['vendor_shipping_to']); ?>"><?php echo esc_html($store_info['vendor_revenue']); ?></a>
    </li>
<?php } ?>

    <?php if (isset($store_info['vendor_employees']) && !empty($store_info['vendor_employees'])) { ?>
    <li>
        <i class="fa fa-users"></i>
        <a style="text-transform: capitalize"
           href="<?php echo esc_html($store_info['vendor_shipping_to']); ?>"><?php echo esc_html($store_info['vendor_employees']); ?></a>
    </li>
<?php } ?>

    <?php if (isset($store_info['vendor_product_range']) && !empty($store_info['vendor_product_range'])) { ?>
    <li>
        <i class="fa fa-sliders"></i>
        <a style="text-transform: capitalize"
           href="<?php echo esc_html($store_info['vendor_product_range']); ?>"><?php echo esc_html($store_info['vendor_product_range']); ?></a>
    </li>
<?php } ?>


<?php }

/*
*
* Add offers to Dokan dashboard
*
* @since 3.0.16
* @package dokan
*
*/
add_filter('dokan_query_var_filter', 'dokan_load_offer_menu');
function dokan_load_offer_menu($query_vars)
{
    $query_vars['offer'] = 'offer';
    return $query_vars;
}

add_filter('dokan_get_dashboard_nav', 'dokan_add_offer_menu');
function dokan_add_offer_menu($urls)
{
    $urls['offer'] = array(
        'title' => __('Offers', 'dokan'),
        'icon' => '<i class="fa fa-bookmark"></i>',
        'url' => dokan_get_navigation_url('offer'),
        'pos' => 51
    );
    return $urls;
}

/*
* Add actions to Offers
* Assign templates
* @since 3.0.16
* @package dokan
*
*/

//Add main offer page
add_action('dokan_load_custom_template', 'dokan_load_template');
function dokan_load_template($query_vars)
{
    if (isset($query_vars['offer'])) {
        require_once dirname(__FILE__) . '/dokan/templates/offers/offers.php';
    }
}

//Add header
add_action('dokan_offer_header', 'render_header_offers');
function render_header_offers()
{
    require_once dirname(__FILE__) . '/dokan/templates/offers/header.php';
}

//Add tabs
add_action('dokan_offer_filter', 'render_filter_offers');
function render_filter_offers()
{
    require_once dirname(__FILE__) . '/dokan/templates/offers/status-filter.php';
}

//Add basic template structure
add_action('dokan_main_content', 'render_content_offers');
function render_content_offers()
{
    require_once dirname(__FILE__) . '/dokan/templates/offers/content.php';
}

//Add Active offer (render)
add_action('dokan_active_offer_table', 'render_active_offer_table');
function render_active_offer_table()
{
    require_once dirname(__FILE__) . '/dokan/templates/offers/active-offers.php';
}

//Add My offers (render)
add_action('dokan_my_offer_table', 'render_my_offer_table');
function render_my_offer_table()
{
    require_once dirname(__FILE__) . '/dokan/templates/offers/my-offers.php';
}

//Add new offer
add_filter('dokan_query_var_filter', 'dokan_load_document_menu_offer');
function dokan_load_document_menu_offer($query_vars)
{
    $query_vars['new-offer'] = 'new-offer';
    return $query_vars;
}

add_action('dokan_load_custom_template', 'dokan_load_template_offer');
function dokan_load_template_offer($query_vars)
{
    if (isset($query_vars['new-offer'])) {
        require_once dirname(__FILE__) . '/dokan/templates/offers/new-offer.php';
    }
}

//Add Edit offer
add_filter('dokan_query_var_filter', 'dokan_load_document_menu_edit_offer');
function dokan_load_document_menu_edit_offer($query_vars)
{
    $query_vars['edit-offer'] = 'edit-offer';
    return $query_vars;
}

//Add Active offer (Permalink)
add_filter('dokan_query_var_filter', 'dokan_load_document_menu_active_offers');
function dokan_load_document_menu_active_offers($query_vars)
{
    $query_vars['active-offers'] = 'active-offers';
    return $query_vars;
}

add_action('dokan_load_custom_template', 'dokan_load_template_active_offers');
function dokan_load_template_active_offers($query_vars)
{
    if (isset($query_vars['active-offers'])) {
        require_once dirname(__FILE__) . '/dokan/templates/offers/active-offers.php';
    }
}

//Show offer applies (Permalink)
add_filter('dokan_query_var_filter', 'dokan_load_document_menu_show_offers');
function dokan_load_document_menu_show_offers($query_vars)
{
    $query_vars['show-offers'] = 'show-offers';
    return $query_vars;
}
