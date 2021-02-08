<?php

use SynerBay\Forms\CreateProduct as CreateProductForm;
use SynerBay\Forms\Validators\Required as RequiredValidator;
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

    new Martfury_Child_WooCommerce();
}
add_action('after_setup_theme', 'myThemeIncludes');

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

add_action('dokan_load_custom_template', 'dokan_load_template_show_offers');
function dokan_load_template_show_offers($query_vars)
{
    if (isset($query_vars['show-offers'])) {
        require_once dirname(__FILE__) . '/dokan/templates/offers/show-offers.php';
    }
}

/*
* Validation for new product
*
* @param array $errors
* @return array $errors
*/
function dokan_can_add_product_validation_customized($errors)
{
    $postData = wp_unslash($_POST);
    $featured_image = absint(sanitize_text_field($postData['feat_image_id']));

    if (empty($featured_image) && !in_array('Please upload a product cover image', $errors)) {
        $errors[] = 'Please upload a product cover image';
    }

    $form = new CreateProductForm($postData);

    if (!$form->validate()) {
        $formErrors = $form->errorMessages();

        $colMap = [
            'weight_unit' => 'Unit',
            'weight_unit_type' => 'Unit Type',
            'material' => 'Material',
        ];

        foreach ($formErrors as $col => &$error) {
            $error = $colMap[$col] . ': ' . $error;
        }

        $errors = array_merge($errors, $formErrors);
    }

    $validator = new RequiredValidator();

    return $errors;
}

add_filter('dokan_can_add_product', 'dokan_can_add_product_validation_customized', 35, 1);
function dokan_new_product_popup_validation_customized($errors, $data)
{
    if (!$data['feat_image_id']) {
        return new WP_Error('no-image', __('Please select AT LEAST ONE Picture', 'dokan-lite'));
    }
    if (!$data['weight_unit']) {
        return new WP_Error('no-weight-unit', __('Please insert product weight unit', 'dokan-lite'));
    }
    if (!$data['material']) {
        return new WP_Error('no-material', __('Please insert product material', 'dokan-lite'));
    }
}

add_filter('dokan_new_product_popup_args', 'dokan_new_product_popup_validation_customized', 35, 2);

//Add custom metafields/details to default product page
add_action('woocommerce_single_product_summary', 'product_custom_details', 13);
function product_custom_details()
{
    global $product, $post, $rfqa;

    if (empty($product)) {
        return;
    }
    // init product rfqa to global
    do_action('synerbay_product_rfqs', $product->get_id());

    $weight_unit = get_post_meta($product->get_id(), '_weight_unit', true);
    $weight_unit_type = get_post_meta($product->get_id(), '_weight_unit_type', true);
    $material = get_post_meta($product->get_id(), '_material', true);
    echo '<div class="product_custom_details">';
    if (!empty($weight_unit)) {
        ?>
        <span class="custom_details"><?php echo esc_attr__('Unit: ', 'dokan-lite'); ?><?php echo esc_attr($weight_unit); ?></span></br>
        <?php
    }
    if (!empty($weight_unit_type)) {
        ?>
        <span class="custom_details"><?php echo esc_attr__('Unit type: ', 'dokan-lite'); ?><?php echo esc_attr($weight_unit_type); ?></span></br>
        <?php
    }
    if (!empty($material)) {
        ?>
        <span class="custom_details"><?php echo esc_attr__('Material: ', 'dokan-lite'); ?><?php echo esc_attr($material); ?></span></br>
        <?php
    }
    echo '</div>';

/*
*
*  Product page
*  RFQ tab
*  Show only if owner
*
*/
    if (is_product() && get_current_user_id() == $post->post_author) {
        add_filter('woocommerce_product_tabs', 'rfq_product_tab');
        function rfq_product_tab($tabs)
        {
            $tabs['rfq'] = array(
                'title' => __('RFQ', 'woocommerce'),
                'priority' => 100,
                'callback' => '_rfq_product_tab_content'
            );
            return $tabs;
        }

        function _rfq_product_tab_content()
        {
            global $rfqs;
            echo '<h2>Request for quotations</h2>';
            echo '
            <div class="notice-box">
                <b>You have received requests for quotation.</b></br>
                After you have added an offer of this product we will inform every customer interested in this offer.</br>
                We also inform all of your followers after every further offer added.</br>

            </div>';
            if (count($rfqs)) {


            } else {
                echo '<br>Remco, kérdezd meg az Andristól, hogy mi legyen a szöveg, ha tök üres, nincs mit megjeleníteni!';
            }
            ?>
            <table class="dokan-table dokan-table-striped rfq" id="rfq-table">
                <thead>
                <tr>
                    <th><?php esc_html_e('Vendor', 'dokan-lite'); ?></th>
                    <th><?php esc_html_e('Quantity', 'dokan-lite'); ?></th>
                    <th><?php esc_html_e('Created', 'dokan-lite'); ?></th>
                    <th><?php esc_html_e('Actions', 'dokan-lite'); ?></th>
                </tr>

                <?php
                foreach ($rfqs as $rfq) {
                    $showOffer = "<a href='" . $rfq['vendor']->get_shop_url() . "' class='dokan-btn dokan-btn-default dokan-btn-sm btn-rfq' data-toggle='tooltip' data-placement='top' title='' data-original-title='Show vendor'><i class='fa fa-eye'>&nbsp;</i></a>";
                    echo '<tr id="rfq_row_' . $rfq['user_id'] . '">'
                        . '<td>' . $rfq['vendor']->get_name() . '</td>'
                        . '<td><b>' . $rfq['qty'] . '</b></td>'
                        . '<td>' . date('Y-m-d', strtotime($rfq['created_at'])) . '</td>'
                        . '<td>' . $showOffer . '</td>'
                        . '</tr>';
                }
                ?>
                </thead>
            </table>

            <?php
        }
    }
    do_action('synerbay_product_buttons');
    echo '<hr>';
}

/**
 * Generate a string of random characters
 *
 * @param array $args The arguments to use for this function
 * @return string|null  The random string generated by this function (only 'if($args['echo'] === false)')
 */
function my_random_string($args = array())
{

    $defaults = array(  // Set some defaults for the function to use
        'characters' => '0123456789',
        'length' => 32,
        'before' => '',
        'after' => '',
        'echo' => false
    );
    $args = wp_parse_args($args, $defaults);    // Parse the args passed by the user with the defualts to generate a final '$args' array

    if (absint($args['length']) < 1) // Ensure that the length is valid
        return;

    $characters_count = strlen($args['characters']);    // Check how many characters the random string is to be assembled from
    for ($i = 0; $i <= $args['length']; $i++) :          // Generate a random character for each of '$args['length']'

        $start = mt_rand(0, $characters_count);
        $random_string .= substr($args['characters'], $start, 1);

    endfor;

    $random_string = $args['before'] . $random_string . $args['after']; // Add the before and after strings to the random string

    if ($args['echo']) : // Check if the random string shoule be output or returned
        echo $random_string;
    else :
        return $random_string;
    endif;

}

/**
 * Upon user registration, generate a random number and add this to the usermeta table
 *
 * @param required integer $user_id The ID of the newly registerd user
 */
add_action('user_register', 'uniq_invite_code');
function uniq_invite_code($user_id)
{

    $args = array(
        'length' => 6,
        'before' => date("Y")
    );
    $random_number = my_random_string($args);
    update_user_meta($user_id, '_invite_code', $random_number);

}

/*
* Allow SVG upload
*/
//function cc_mime_types($mimes) {
//    $mimes['svg'] = 'image/svg+xml';
//    return $mimes;
//}
//add_filter('upload_mimes', 'cc_mime_types');

/*
*
* Dokan Dashboard / General
* @since 3.0.16
* @package dokan
*
*/

//Change icons on Dokan dashboard
add_filter ('dokan_get_dashboard_nav','change_dokan_dashboard_icon',16);
function change_dokan_dashboard_icon($urls){
    $urls['products']['icon'] = '<span id="svg-icon" class="icon-products"></span>';
    $urls['offer']['icon']    = '<span id="svg-icon" class="icon-offer"></span>';
    $urls['orders']['icon']   = '<span id="svg-icon" class="icon-orders"></span>';
    return $urls;
}

//rename products
add_filter ('dokan_get_dashboard_nav','rename_dashboard_product',16);
function rename_dashboard_product($urls){
    $urls['products']['title'] = __( 'Catalogue', 'dokan-lite' );
    return $urls;
}

//remove coupons from dashboard
function dokan_remove_coupon_menu( $urls ) {
    unset($urls["coupons"]);
    return $urls;
}
add_filter('dokan_get_dashboard_nav', 'dokan_remove_coupon_menu', 16 );

/*
*
* Woocommerce custom changes
* @version 4.9.1
*
*/

//Completely remove cart from woocommerce
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
remove_action('woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30);

//Message when no products found
add_action( 'woocommerce_no_products_found', function(){
    remove_action( 'woocommerce_no_products_found', 'wc_no_products_found', 10 ); ?>
    <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearch'); ?></p>'
    <?php
}, 9 );

//Remove item from menu - Customer account page
add_filter ( 'woocommerce_account_menu_items', 'my_account_remove_menu_item' );
function my_account_remove_menu_item( $menu_links ){
    //unset( $menu_links['edit-address'] ); // Addresses
    //unset( $menu_links['dashboard'] ); // Remove Dashboard
    //unset( $menu_links['payment-methods'] ); // Remove Payment Methods
    //unset( $menu_links['orders'] ); // Remove Orders
    unset( $menu_links['downloads'] ); // Disable Downloads
    //unset( $menu_links['edit-account'] ); // Remove Account details tab
    //unset( $menu_links['customer-logout'] ); // Remove Logout link

    return $menu_links;
}

//Remove cancel button
add_filter('woocommerce_my_account_my_orders_actions', 'remove_myaccount_orders_cancel_button', 10, 2);
function remove_myaccount_orders_cancel_button( $actions, $order ){
    unset($actions['pay']);
    unset($actions['cancel']);
    return $actions;
}

//Change number or products per row to 5
add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
    function loop_columns() {
        return 5;
    }
}

//Account page - Add active offers page
add_filter ( 'woocommerce_account_menu_items', 'my_account_active_offers', 40 );
function my_account_active_offers( $menu_links ){

    $menu_links = array_slice( $menu_links, 0, 1, true )
        + array( 'active-offers' => 'Active Offers' )
        + array_slice( $menu_links, 1, NULL, true );

    return $menu_links;

}

//Init endpoint
add_action( 'init', 'active_offers_add_endpoint' );
function active_offers_add_endpoint() {
    add_rewrite_endpoint( 'active-offers', EP_PAGES );
}

//Active offer content
add_action( 'woocommerce_account_active-offers_endpoint', 'active_offers_my_account_endpoint_content' );
function active_offers_my_account_endpoint_content() {
    do_action('synerbay_init_global_my_offer_applies_for_dashboard');
    global $myOfferApplies; ?>
    <!-- Start -->
    <button class="dokan-btn dokan-btn-theme" onClick="window.location.reload();"><i class="fa fa-refresh">&nbsp;</i> Refresh active offers</button>
    </br></br>
    <table class="dokan-table dokan-table-striped product-listing-table dokan-inline-editable-table"
           id="dokan-product-list-table">
        <thead>
        <tr>
            <th><?php esc_html_e('Offer ID', 'dokan-lite'); ?></th>
            <th><?php esc_html_e('Product name', 'dokan-lite'); ?></th>
            <th><?php esc_html_e('Quantity', 'dokan-lite'); ?></th>
            <th><?php esc_html_e('Current price', 'dokan-lite'); ?></th>
            <th><?php esc_html_e('Current quantity', 'dokan-lite'); ?></th>
            <th><?php esc_html_e('Offer end date', 'dokan-lite'); ?></th>
            <th><?php esc_html_e('Actions', 'dokan-lite'); ?></th>
        </tr>
        <?php
        $currentDate = strtotime(date('Y-m-d H:i:s'));
        foreach ($myOfferApplies as $offerApply) {

            $deleteButton = '';
            $showOfferButton = "<a href='" . $offerApply['offer']['url'] . "' class='dokan-btn dokan-btn-default dokan-btn-sm tips'data-toggle='tooltip' data-placement='top' title='' data-original-title='Details'><i class='fa fa-eye'>&nbsp;</i></a>";

            if ($currentDate <= strtotime($offerApply['offer']['offer_end_date'])) {
                $deleteButton = "<a onclick='window.synerbay.disAppearOfferDashboard(" . $offerApply['offer_id'] . ")' class='dokan-btn dokan-btn-default dokan-btn-sm tips' data-toggle='tooltip' data-placement='top' title='' data-original-title='Delete'><i class='fa fa-times'>&nbsp;</i></a>";
            }

            echo '<tr id="my_active_offer_row_' . $offerApply['offer_id'] . '">'
                . '<td>' . $offerApply['offer_id'] . '</td>'
                . '<td>' . $offerApply['offer']['product']['post_title'] . '</td>'
                . '<td>' . $offerApply['qty'] . '</td>'
                . '<td><b>' . $offerApply['offer']['summary']['formatted_actual_product_price'] . '</b></td>'
                . '<td><b>' . $offerApply['offer']['summary']['actual_applicant_product_number'] . '</b></td>'
                . '<td><b>' . date('Y-m-d', strtotime($offerApply['offer']['offer_end_date'])) . '</b></td>'
                . '<td>' . $deleteButton . $showOfferButton . '</td>'
                . '</tr>';
        }

        if (!$myOfferApplies) {
            echo '<td colspan="7">No active offers found</td>';
        } ?>
        </thead>
    </table>
    <!-- End -->
    <?php
}

//Product search results / sorting
function remove_woocommerce_catalog_orderby( $orderby ) {
    unset($orderby["price"]);
    unset($orderby["price-desc"]);
    return $orderby;
}
add_filter( "woocommerce_catalog_orderby", "remove_woocommerce_catalog_orderby", 20 );

//Change the product tab order
add_filter( 'woocommerce_product_tabs', 'woocommerce_change_tabs_order' );
function woocommerce_change_tabs_order( $tabs ) {
    $tabs['more_seller_product']['priority'] = 5;
    return $tabs;
}

/*
 *
 *  || Dokan Wizard
 *
 */
class Dokan_Setup_Wizard_Override extends Dokan_Seller_Setup_Wizard {
    /**
     * Introduction step.
     */
    public function dokan_setup_introduction() {
        $dashboard_url = dokan_get_navigation_url();
        ?>
        <h1><?php esc_attr_e( 'Welcome to the Marketplace!', 'dokan-lite' ); ?></h1>
        <p><?php echo wp_kses( __( 'Thank you for choosing The Marketplace to power your online store! This quick setup wizard will help you configure the basic settings. <strong>It’s completely optional and shouldn’t take longer than two minutes.</strong>', 'dokan-lite' ), [ 'strong' => [] ] ); ?></p>
        <p class="wc-setup-actions step">
            <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next lets-go-btn dokan-btn-theme"><?php esc_attr_e( 'Let\'s Go!', 'dokan-lite' ); ?></a>
        </p>
        <?php
        do_action( 'dokan_seller_wizard_introduction', $this );
    }

    /**
     * Store step.
     */
    public function dokan_setup_store() {
        $store_info      = $this->store_info;

        $store_ppp       = isset( $store_info['store_ppp'] ) ? esc_attr( $store_info['store_ppp'] ) : 10;
        $show_email      = isset( $store_info['show_email'] ) ? esc_attr( $store_info['show_email'] ) : 'no';
        $address_street1 = isset( $store_info['address']['street_1'] ) ? $store_info['address']['street_1'] : '';
        $address_street2 = isset( $store_info['address']['street_2'] ) ? $store_info['address']['street_2'] : '';
        $address_city    = isset( $store_info['address']['city'] ) ? $store_info['address']['city'] : '';
        $address_zip     = isset( $store_info['address']['zip'] ) ? $store_info['address']['zip'] : '';
        $address_country = isset( $store_info['address']['country'] ) ? $store_info['address']['country'] : '';
        $address_state   = isset( $store_info['address']['state'] ) ? $store_info['address']['state'] : '';

        $vendor_vat      = isset( $store_info['vendor_vat']) ? $store_info['vendor_vat'] : '';
        $vendor_type     = isset( $store_info['vendor_type']) ? $store_info['vendor_type'] : '';
        $vendor_shipping_to     = isset( $store_info['vendor_shipping_to']) ? $store_info['vendor_shipping_to'] : '';
        $vendor_revenue      = isset( $store_info['vendor_revenue']) ? $store_info['vendor_revenue'] : '';
        $vendor_employees    = isset( $store_info['vendor_employees']) ? $store_info['vendor_employees'] : '';
        $vendor_product_range    = isset( $store_info['vendor_product_range']) ? $store_info['vendor_product_range'] : '';
        $vendor_industry   = isset( $store_info['vendor_industry']) ? $store_info['vendor_industry'] : '';

        $country_obj   = new WC_Countries();
        $countries     = $country_obj->countries;
        $states        = $country_obj->states;
        ?>
        <h1><?php esc_attr_e( 'Store Setup', 'dokan-lite' ); ?></h1>
        <form method="post" class="dokan-seller-setup-form">
            <table class="form-table">
                <tr>
                    <!--
                    <th scope="row"><label for="store_ppp"><?php esc_attr_e( 'Store Product Per Page', 'dokan-lite' ); ?></label></th>
                    <td>
                        <input type="hidden" id="store_ppp" name="store_ppp" value="<?php echo esc_attr( $store_ppp ); ?>" />
                    </td>
-->
                </tr>
                <!-- Ask vat  -->
                <th scope="row"><label for="vendor_vat"><?php esc_html_e( 'VAT Number (*)', 'dokan-lite' ); ?></label></th>
                <td>
                    <input type="text" required="required" class="dokan-form-control input-md valid" name="vendor_vat" id="reg_seller_url" value="<?php echo $vendor_vat; ?>" />
                </td>
                <tr>

                    <!-- Ask Industry -->
                    <th scope="row"><label for="vendor_industry"><?php esc_html_e( 'Industry (*)', 'dokan-lite' ); ?></label></th>
                    <td>
                        <select required="required" class="wc-enhanced-select" name="vendor_industry" id="vendor_industry">
                            <?php
                            $industries = [
                                '' => __( 'Select industry' ),
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

                            foreach ( $industries as $value => $label ) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    $value,
                                    selected( $value, $vendor_industry, false ),
                                    $label
                                );
                            }
                            ?>
                        </select>
                    </td>
                <tr>
                    <!-- Ask Company Type -->
                    <th scope="row"><label for="vendor_type"><?php esc_html_e( 'Company Type (*)', 'dokan-lite' ); ?></label></th>
                    <td>
                        <select required="required" class="wc-enhanced-select" name="vendor_type" id="vendor_type">
                            <?php
                            $company_types = [
                                '' => __( 'Select company type' ),
                                'manufacturer' => ('Manufacturer'),
                                'wholesaler' => ('Wholesaler'),
                                'retailer' => ('Retailer'),
                                'service' => ('Service'),
                            ];

                            foreach ( $company_types as $value => $label ) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    $value,
                                    selected( $value, $vendor_type, false ),
                                    $label
                                );
                            }
                            ?>
                        </select>
                    </td>
                <tr>

                    <!-- Ask where company ships -->
                    <th scope="row"><label for="vendor_revenue"><?php esc_html_e( 'Shipping to (*)', 'dokan-lite' ); ?></label></th>
                    <td>
                        <select required="required" class="wc-enhanced-select" name="vendor_shipping_to" id="vendor_shipping_to">
                            <?php
                            $countries_shipping = array
                            (
                                '' => __( 'Select shipping to' ),
                                'Worldwide' => 'Wordwide',
                                'Asia' => 'Asia',
                                'Australia' => 'Australia',
                                'Europe' => 'Europe',
                                'Africa' => 'Africa',
                                'North America' => 'North America',
                                'South America'=> 'South America',
                                'Antarctica' => 'Antarctica',
                                'AF' => 'Afghanistan',
                                'AX' => 'Aland Islands',
                                'AL' => 'Albania',
                                'DZ' => 'Algeria',
                                'AS' => 'American Samoa',
                                'AD' => 'Andorra',
                                'AO' => 'Angola',
                                'AI' => 'Anguilla',
                                'AQ' => 'Antarctica',
                                'AG' => 'Antigua And Barbuda',
                                'AR' => 'Argentina',
                                'AM' => 'Armenia',
                                'AW' => 'Aruba',
                                'AU' => 'Australia',
                                'AT' => 'Austria',
                                'AZ' => 'Azerbaijan',
                                'BS' => 'Bahamas',
                                'BH' => 'Bahrain',
                                'BD' => 'Bangladesh',
                                'BB' => 'Barbados',
                                'BY' => 'Belarus',
                                'BE' => 'Belgium',
                                'BZ' => 'Belize',
                                'BJ' => 'Benin',
                                'BM' => 'Bermuda',
                                'BT' => 'Bhutan',
                                'BO' => 'Bolivia',
                                'BA' => 'Bosnia And Herzegovina',
                                'BW' => 'Botswana',
                                'BV' => 'Bouvet Island',
                                'BR' => 'Brazil',
                                'IO' => 'British Indian Ocean Territory',
                                'BN' => 'Brunei Darussalam',
                                'BG' => 'Bulgaria',
                                'BF' => 'Burkina Faso',
                                'BI' => 'Burundi',
                                'KH' => 'Cambodia',
                                'CM' => 'Cameroon',
                                'CA' => 'Canada',
                                'CV' => 'Cape Verde',
                                'KY' => 'Cayman Islands',
                                'CF' => 'Central African Republic',
                                'TD' => 'Chad',
                                'CL' => 'Chile',
                                'CN' => 'China',
                                'CX' => 'Christmas Island',
                                'CC' => 'Cocos (Keeling) Islands',
                                'CO' => 'Colombia',
                                'KM' => 'Comoros',
                                'CG' => 'Congo',
                                'CD' => 'Congo, Democratic Republic',
                                'CK' => 'Cook Islands',
                                'CR' => 'Costa Rica',
                                'CI' => 'Cote D\'Ivoire',
                                'HR' => 'Croatia',
                                'CU' => 'Cuba',
                                'CY' => 'Cyprus',
                                'CZ' => 'Czech Republic',
                                'DK' => 'Denmark',
                                'DJ' => 'Djibouti',
                                'DM' => 'Dominica',
                                'DO' => 'Dominican Republic',
                                'EC' => 'Ecuador',
                                'EG' => 'Egypt',
                                'SV' => 'El Salvador',
                                'GQ' => 'Equatorial Guinea',
                                'ER' => 'Eritrea',
                                'EE' => 'Estonia',
                                'ET' => 'Ethiopia',
                                'FK' => 'Falkland Islands (Malvinas)',
                                'FO' => 'Faroe Islands',
                                'FJ' => 'Fiji',
                                'FI' => 'Finland',
                                'FR' => 'France',
                                'GF' => 'French Guiana',
                                'PF' => 'French Polynesia',
                                'TF' => 'French Southern Territories',
                                'GA' => 'Gabon',
                                'GM' => 'Gambia',
                                'GE' => 'Georgia',
                                'DE' => 'Germany',
                                'GH' => 'Ghana',
                                'GI' => 'Gibraltar',
                                'GR' => 'Greece',
                                'GL' => 'Greenland',
                                'GD' => 'Grenada',
                                'GP' => 'Guadeloupe',
                                'GU' => 'Guam',
                                'GT' => 'Guatemala',
                                'GG' => 'Guernsey',
                                'GN' => 'Guinea',
                                'GW' => 'Guinea-Bissau',
                                'GY' => 'Guyana',
                                'HT' => 'Haiti',
                                'HM' => 'Heard Island & Mcdonald Islands',
                                'VA' => 'Holy See (Vatican City State)',
                                'HN' => 'Honduras',
                                'HK' => 'Hong Kong',
                                'HU' => 'Hungary',
                                'IS' => 'Iceland',
                                'IN' => 'India',
                                'ID' => 'Indonesia',
                                'IR' => 'Iran, Islamic Republic Of',
                                'IQ' => 'Iraq',
                                'IE' => 'Ireland',
                                'IM' => 'Isle Of Man',
                                'IL' => 'Israel',
                                'IT' => 'Italy',
                                'JM' => 'Jamaica',
                                'JP' => 'Japan',
                                'JE' => 'Jersey',
                                'JO' => 'Jordan',
                                'KZ' => 'Kazakhstan',
                                'KE' => 'Kenya',
                                'KI' => 'Kiribati',
                                'KR' => 'Korea',
                                'KW' => 'Kuwait',
                                'KG' => 'Kyrgyzstan',
                                'LA' => 'Lao People\'s Democratic Republic',
                                'LV' => 'Latvia',
                                'LB' => 'Lebanon',
                                'LS' => 'Lesotho',
                                'LR' => 'Liberia',
                                'LY' => 'Libyan Arab Jamahiriya',
                                'LI' => 'Liechtenstein',
                                'LT' => 'Lithuania',
                                'LU' => 'Luxembourg',
                                'MO' => 'Macao',
                                'MK' => 'Macedonia',
                                'MG' => 'Madagascar',
                                'MW' => 'Malawi',
                                'MY' => 'Malaysia',
                                'MV' => 'Maldives',
                                'ML' => 'Mali',
                                'MT' => 'Malta',
                                'MH' => 'Marshall Islands',
                                'MQ' => 'Martinique',
                                'MR' => 'Mauritania',
                                'MU' => 'Mauritius',
                                'YT' => 'Mayotte',
                                'MX' => 'Mexico',
                                'FM' => 'Micronesia, Federated States Of',
                                'MD' => 'Moldova',
                                'MC' => 'Monaco',
                                'MN' => 'Mongolia',
                                'ME' => 'Montenegro',
                                'MS' => 'Montserrat',
                                'MA' => 'Morocco',
                                'MZ' => 'Mozambique',
                                'MM' => 'Myanmar',
                                'NA' => 'Namibia',
                                'NR' => 'Nauru',
                                'NP' => 'Nepal',
                                'NL' => 'Netherlands',
                                'AN' => 'Netherlands Antilles',
                                'NC' => 'New Caledonia',
                                'NZ' => 'New Zealand',
                                'NI' => 'Nicaragua',
                                'NE' => 'Niger',
                                'NG' => 'Nigeria',
                                'NU' => 'Niue',
                                'NF' => 'Norfolk Island',
                                'MP' => 'Northern Mariana Islands',
                                'NO' => 'Norway',
                                'OM' => 'Oman',
                                'PK' => 'Pakistan',
                                'PW' => 'Palau',
                                'PS' => 'Palestinian Territory, Occupied',
                                'PA' => 'Panama',
                                'PG' => 'Papua New Guinea',
                                'PY' => 'Paraguay',
                                'PE' => 'Peru',
                                'PH' => 'Philippines',
                                'PN' => 'Pitcairn',
                                'PL' => 'Poland',
                                'PT' => 'Portugal',
                                'PR' => 'Puerto Rico',
                                'QA' => 'Qatar',
                                'RE' => 'Reunion',
                                'RO' => 'Romania',
                                'RU' => 'Russian Federation',
                                'RW' => 'Rwanda',
                                'BL' => 'Saint Barthelemy',
                                'SH' => 'Saint Helena',
                                'KN' => 'Saint Kitts And Nevis',
                                'LC' => 'Saint Lucia',
                                'MF' => 'Saint Martin',
                                'PM' => 'Saint Pierre And Miquelon',
                                'VC' => 'Saint Vincent And Grenadines',
                                'WS' => 'Samoa',
                                'SM' => 'San Marino',
                                'ST' => 'Sao Tome And Principe',
                                'SA' => 'Saudi Arabia',
                                'SN' => 'Senegal',
                                'RS' => 'Serbia',
                                'SC' => 'Seychelles',
                                'SL' => 'Sierra Leone',
                                'SG' => 'Singapore',
                                'SK' => 'Slovakia',
                                'SI' => 'Slovenia',
                                'SB' => 'Solomon Islands',
                                'SO' => 'Somalia',
                                'ZA' => 'South Africa',
                                'GS' => 'South Georgia And Sandwich Isl.',
                                'ES' => 'Spain',
                                'LK' => 'Sri Lanka',
                                'SD' => 'Sudan',
                                'SR' => 'Suriname',
                                'SJ' => 'Svalbard And Jan Mayen',
                                'SZ' => 'Swaziland',
                                'SE' => 'Sweden',
                                'CH' => 'Switzerland',
                                'SY' => 'Syrian Arab Republic',
                                'TW' => 'Taiwan',
                                'TJ' => 'Tajikistan',
                                'TZ' => 'Tanzania',
                                'TH' => 'Thailand',
                                'TL' => 'Timor-Leste',
                                'TG' => 'Togo',
                                'TK' => 'Tokelau',
                                'TO' => 'Tonga',
                                'TT' => 'Trinidad And Tobago',
                                'TN' => 'Tunisia',
                                'TR' => 'Turkey',
                                'TM' => 'Turkmenistan',
                                'TC' => 'Turks And Caicos Islands',
                                'TV' => 'Tuvalu',
                                'UG' => 'Uganda',
                                'UA' => 'Ukraine',
                                'AE' => 'United Arab Emirates',
                                'GB' => 'United Kingdom',
                                'US' => 'United States',
                                'UM' => 'United States Outlying Islands',
                                'UY' => 'Uruguay',
                                'UZ' => 'Uzbekistan',
                                'VU' => 'Vanuatu',
                                'VE' => 'Venezuela',
                                'VN' => 'Viet Nam',
                                'VG' => 'Virgin Islands, British',
                                'VI' => 'Virgin Islands, U.S.',
                                'WF' => 'Wallis And Futuna',
                                'EH' => 'Western Sahara',
                                'YE' => 'Yemen',
                                'ZM' => 'Zambia',
                                'ZW' => 'Zimbabwe',
                            );


                            foreach ( $countries_shipping as $value => $label ) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    $value,
                                    selected( $value, $vendor_shipping_to, false ),
                                    $label
                                );
                            }
                            ?>
                        </select>
                    </td>
                <tr>

                    <!-- Ask for Annual rev -->
                    <th scope="row"><label for="vendor_revenue"><?php esc_html_e( 'Annual Revenue (*)', 'dokan-lite' ); ?></label></th>
                    <td>
                        <select required="required" class="wc-enhanced-select" name="vendor_revenue" id="vendor_revenue">
                            <?php
                            $revenues = [
                                '' => __( 'Select annual revenue' ),
                                '$0-$500.000' => ('$0 - $500.000'),
                                '$500.000-$1.000.000' => ('$500.000 - $1.000.000'),
                                '$1.000.000-$5.000.000' => ('$1.000.000 - $5.000.000'),
                                '$5.000.000-$10.000.000' =>('$5.000.000 - $10.000.000'),
                                '$10.000.000-$50.000.000' => ('$10.000.000 - $50.000.000'),
                                '$50.000.000<' => ('$50.000.000<'),

                            ];

                            foreach ( $revenues as $value => $label ) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    $value,
                                    selected( $value, $vendor_revenue, false ),
                                    $label
                                );
                            }
                            ?>
                        </select>
                    </td>
                <tr>

                    <!-- Ask employees -->
                    <th scope="row"><label for="vendor_employees"><?php esc_html_e( 'Employees (*)', 'dokan-lite' ); ?></label></th>
                    <td>
                        <select required="required" class="wc-enhanced-select" name="vendor_employees" id="vendor_employees">
                            <?php
                            $employees = [
                                '' => __( 'Select employees count' ),
                                '<10 employees' => ('< 10 employees'),
                                '10-50 employees' => ('10 - 50 employees'),
                                '50-100 employees' => ('50 - 100 employees'),
                                '100-500 employees' => ('100 - 500 employees'),
                                '500-1000 employees' => ('500 - 1000 employees'),
                                '1000< employees' => ('1000 < employees'),
                            ];

                            foreach ( $employees as $value => $label ) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    $value,
                                    selected( $value, $vendor_employees, false ),
                                    $label
                                );
                            }
                            ?>
                        </select>
                    </td>
                <tr>

                    <!-- Ask Product range -->
                    <th scope="row"><label for="vendor_product_range"><?php esc_html_e( 'Product Range (*)', 'dokan-lite' ); ?></label></th>
                    <td>
                        <select required="required" class="wc-enhanced-select" name="vendor_product_range" id="vendor_product_range">
                            <?php
                            $product_ranges = [
                                '' => __( 'Select a product range' ),
                                '<10 products' => ('< 10 products'),
                                '10-50 products' => ('10 - 50 products'),
                                '50-100 products' => ('50 - 100 products'),
                                '100-300 products' => ('100 - 300 products'),
                                '300-1000 products' => ('300 - 1000 products'),
                                '1000< products' => ('1000 < products'),
                            ];

                            foreach ( $product_ranges as $value => $label ) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    $value,
                                    selected( $value, $vendor_product_range, false ),
                                    $label
                                );
                            }
                            ?>
                        </select>
                    </td>

                    <!-- Dokan store question -->
                <tr>
                    <th scope="row"><label for="address[street_1]"><?php esc_html_e( 'Street (*)', 'dokan-lite' ); ?></label></th>
                    <td>
                        <input required="required" type="text" id="address[street_1]" name="address[street_1]" value="<?php echo esc_attr( $address_street1 ); ?>" />
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="address[street_2]"><?php esc_html_e( 'Street 2', 'dokan-lite' ); ?></label></th>
                    <td>
                        <input type="text" id="address[street_2]" name="address[street_2]" value="<?php echo esc_attr( $address_street2 ); ?>" />
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="address[city]"><?php esc_html_e( 'City (*)', 'dokan-lite' ); ?></label></th>
                    <td>
                        <input required="required" type="text" id="address[city]" name="address[city]" value="<?php echo esc_attr( $address_city ); ?>" />
                    </td>
                </tr>
                <th scope="row"><label for="address[zip]"><?php esc_html_e( 'Post/Zip Code (*)', 'dokan-lite' ); ?></label></th>
                <td>
                    <input required="required" type="text" id="address[zip]" name="address[zip]" value="<?php echo esc_attr( $address_zip ); ?>" />
                </td>
                <tr>
                </tr>

                <th scope="row"><label for="address[country]"><?php esc_html_e( 'Country (*)', 'dokan-lite' ); ?></label></th>
                <td>
                    <select required="required" name="address[country]" class="wc-enhanced-select country_to_state" id="address[country]">
                        <?php dokan_country_dropdown( $countries, $address_country, false ); ?>
                    </select>
                </td>
                </tr>

                <tr>
                    <th scope="row"><label for="calc_shipping_state"><?php esc_html_e( 'State', 'dokan-lite' ); ?></label></th>
                    <td>
                        <input required="required" type="text" id="calc_shipping_state" name="address[state]" value="<?php echo esc_attr( $address_state ); ?>" / placeholder="<?php esc_attr_e( 'State Name', 'dokan-lite' ); ?>">
                    </td>
                </tr>

                <?php do_action( 'dokan_seller_wizard_store_setup_after_address_field', $this ); ?>

                <tr style="display: none">
                    <th scope="row"><label for="show_email"><?php esc_html_e( 'Email (*)', 'dokan-lite' ); ?></label></th>
                    <td class="checkbox">
                        <input checked type="checkbox" name="show_email" id="show_email" class="switch-input" value="1" <?php echo ( $show_email == 'yes' ) ? 'checked="true"' : ''; ?>>
                        <label for="show_email">
                            <?php esc_html_e( 'Show email address in store', 'dokan-lite' ); ?>
                        </label>
                    </td>
                </tr>

                <?php do_action( 'dokan_seller_wizard_store_setup_field', $this ); ?>

            </table>
            <p class="wc-setup-actions step">
                <input type="submit" class="button-primary button button-large button-next store-step-continue dokan-btn-theme" value="<?php esc_attr_e( 'Continue', 'dokan-lite' ); ?>" name="save_step" />
                <!--<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next store-step-skip-btn dokan-btn-theme"><?php esc_html_e( 'Skip this step', 'dokan-lite' ); ?></a>-->
                <?php wp_nonce_field( 'dokan-seller-setup' ); ?>
            </p>
        </form>

        <script>
            (function($){
                var states = <?php echo json_encode( $states ); ?>;

                $('body').on( 'change', 'select.country_to_state, input.country_to_state', function() {
                    // Grab wrapping element to target only stateboxes in same 'group'
                    var $wrapper    = $( this ).closest('form.dokan-seller-setup-form');

                    var country     = $( this ).val(),
                        $statebox   = $wrapper.find( '#calc_shipping_state' ),
                        $parent     = $statebox.closest('tr'),
                        input_name  = $statebox.attr( 'name' ),
                        input_id    = $statebox.attr( 'id' ),
                        value       = $statebox.val(),
                        placeholder = $statebox.attr( 'placeholder' ) || $statebox.attr( 'data-placeholder' ) || '',
                        state_option_text = '<?php echo esc_attr__( 'Select an option&hellip;', 'dokan-lite' ); ?>';

                    if ( states[ country ] ) {
                        if ( $.isEmptyObject( states[ country ] ) ) {
                            $statebox.closest('tr').hide().find( '.select2-container' ).remove();
                            $statebox.replaceWith( '<input type="hidden" class="hidden" name="' + input_name + '" id="' + input_id + '" value="" placeholder="' + placeholder + '" />' );

                            $( document.body ).trigger( 'country_to_state_changed', [ country, $wrapper ] );

                        } else {

                            var options = '',
                                state = states[ country ];

                            for( var index in state ) {
                                if ( state.hasOwnProperty( index ) ) {
                                    options = options + '<option value="' + index + '">' + state[ index ] + '</option>';
                                }
                            }

                            $statebox.closest('tr').show();

                            if ( $statebox.is( 'input' ) ) {
                                // Change for select
                                $statebox.replaceWith( '<select name="' + input_name + '" id="' + input_id + '" class="wc-enhanced-select state_select" data-placeholder="' + placeholder + '"></select>' );
                                $statebox = $wrapper.find( '#calc_shipping_state' );
                            }

                            $statebox.html( '<option value="">' + state_option_text + '</option>' + options );
                            $statebox.val( value ).change();

                            $( document.body ).trigger( 'country_to_state_changed', [country, $wrapper ] );

                        }
                    } else {
                        if ( $statebox.is( 'select' ) ) {

                            $parent.show().find( '.select2-container' ).remove();
                            $statebox.replaceWith( '<input type="text" class="input-text" name="' + input_name + '" id="' + input_id + '" placeholder="' + placeholder + '" />' );

                            $( document.body ).trigger( 'country_to_state_changed', [country, $wrapper ] );

                        } else if ( $statebox.is( 'input[type="hidden"]' ) ) {

                            $parent.show().find( '.select2-container' ).remove();
                            $statebox.replaceWith( '<input type="text" class="input-text" name="' + input_name + '" id="' + input_id + '" placeholder="' + placeholder + '" />' );

                            $( document.body ).trigger( 'country_to_state_changed', [country, $wrapper ] );

                        }
                    }

                    $( document.body ).trigger( 'country_to_state_changing', [country, $wrapper ] );
                    $('.wc-enhanced-select').select2();
                });

                $( ':input.country_to_state' ).change();

            })(jQuery)

        </script>
        <?php

        do_action( 'dokan_seller_wizard_after_store_setup_form', $this );
    }

    /**
     * Save store options.
     */
    public function dokan_setup_store_save() {
        if ( ! isset( $_POST['_wpnonce'] ) ) {
            return;
        }

        $nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) );

        if ( ! wp_verify_nonce( $nonce, 'dokan-seller-setup' ) ) {
            return;
        }

        $dokan_settings = $this->store_info;

        $dokan_settings['store_ppp']   = isset( $_POST['store_ppp'] ) ? absint( $_POST['store_ppp'] ) : '';
        $dokan_settings['vendor_vat']  = isset( $_POST['vendor_vat'] ) ? ( $_POST['vendor_vat'] ) : '';
        $dokan_settings['vendor_industry']  = isset( $_POST['vendor_industry'] ) ? ( $_POST['vendor_industry'] ) : '';
        $dokan_settings['vendor_type']  = isset( $_POST['vendor_type'] ) ? ( $_POST['vendor_type'] ) : '';
        $dokan_settings['vendor_shipping_to']  = isset( $_POST['vendor_shipping_to'] ) ? ( $_POST['vendor_shipping_to'] ) : '';
        $dokan_settings['vendor_revenue']  = isset( $_POST['vendor_revenue'] ) ? ( $_POST['vendor_revenue'] ) : '';
        $dokan_settings['vendor_employees']  = isset( $_POST['vendor_employees'] ) ? ( $_POST['vendor_employees'] ) : '';
        $dokan_settings['vendor_product_range']  = isset( $_POST['vendor_product_range'] ) ? ( $_POST['vendor_product_range'] ) : '';



        $dokan_settings['address']     = isset( $_POST['address'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['address'] ) ) : array();
        $dokan_settings['show_email']  = isset( $_POST['show_email'] ) ? 'yes' : 'no';

        // Check address and add manually values on Profile Completion also increase progress value
        if ( $dokan_settings['address'] ) {
            $dokan_settings['profile_completion']['address']   = 10;
            $profile_settings                                  = get_user_meta( $this->store_id, 'dokan_profile_settings', true );

            if ( ! empty( $profile_settings['profile_completion']['progress'] ) ) {
                $dokan_settings['profile_completion']['progress'] = $profile_settings['profile_completion']['progress'] + 10;
            }
        }

        update_user_meta( $this->store_id, 'dokan_profile_settings', $dokan_settings );

        do_action( 'dokan_seller_wizard_store_field_save', $this );

        wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
        exit;
    }
    /**
     * payment step.
     */
    public function dokan_setup_payment() {
        $methods    = dokan_withdraw_get_active_methods();
        $store_info = $this->store_info;
        ?>
        <h1><?php esc_html_e( 'Payment Setup', 'dokan-lite' ); ?></h1>
        <form method="post">
            <table class="form-table">
                <?php
                foreach ( $methods as $method_key ) {
                    $method = dokan_withdraw_get_method( $method_key );
                    if ( isset( $method['callback'] ) && is_callable( $method['callback'] ) ) {
                        ?>
                        <tr>
                            <th scope="row"><label><?php echo esc_html( $method['title'] ); ?></label></th>
                            <td>
                                <?php call_user_func( $method['callback'], $store_info ); ?>
                            </td>
                        </tr>
                        <?php
                    }
                }

                do_action( 'dokan_seller_wizard_payment_setup_field', $this );
                ?>
            </table>
            <p class="wc-setup-actions step">
                <input type="submit" class="button-primary button button-large button-next payment-continue-btn dokan-btn-theme" value="<?php esc_attr_e( 'Continue', 'dokan-lite' ); ?>" name="save_step" />
                <?php wp_nonce_field( 'dokan-seller-setup' ); ?>
            </p>
        </form>
        <?php

        do_action( 'dokan_seller_wizard_after_payment_setup_form', $this );
    }
}

new Dokan_Setup_Wizard_Override;