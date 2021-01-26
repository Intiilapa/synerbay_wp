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
    <hr>
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
            <p>
                Andris:<br>Ide le kell írnunk, hogy amennyiben az adott termékből offer készül, akkor annak elindulásakor minden egyes igénylőt 
                kiértesítünk e-mail-ben, majd töröljük az igényét a termékről.<br>
                <strong>Információ</strong>: nem csak az igénylőket, hanem a személyes követőidet is kiértsítjük minden egyes esetben, amikor egy általad 
                készített ajánlat elindul, viszont ebben az esetben értelemszerűen nem kerülnek törlésre a követések.
            </p>';
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
    $urls['products']['icon'] = '<span class="icon-products"></span>';
    $urls['offer']['icon']    = '<span class="icon-offer"></span>';
    $urls['orders']['icon']   = '<span class="icon-orders"></span>';
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
