<?php
add_action( 'wp_enqueue_scripts', 'martfury_child_enqueue_scripts', 20 );
function martfury_child_enqueue_scripts() {
    wp_enqueue_style( 'martfury-child-style', get_stylesheet_uri() );
    wp_enqueue_style('child-style-custom', get_stylesheet_directory_uri() . '/custom.css');
	if ( is_rtl() ) {
		wp_enqueue_style( 'martfury-rtl', get_template_directory_uri() . '/rtl.css', array(), '20180105' );
	}

}

add_action('wp_footer', 'custom_footer_actions');
function custom_footer_actions(){
    do_action('synerbay_loader');
    do_action('synerbay_loginModal');
};

//add_filter( 'template_include', 'single_offer_template_include', 50, 1 );
//function single_offer_template_include( $template )
//{
//    if (is_singular('product') && (has_term('offer', 'product_cat'))) {
//        $offerTemplate = require_once dirname(__FILE__) . "/woocommerce/single-product.php";
//        return $offerTemplate;
//    } else
//        $template = require_once dirname(__FILE__) . "/woocommerce/single-product.php";
//        return $template;
//}

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

     <!-- Company industry -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Industry', 'dokan' ); ?>
         </label>

         <div class="dokan-w5">
             <select class="dokan-form-control" name="vendor_industry" id="vendor_industry">
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
         </div>
     </div>

     <!-- Company type -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Company Type', 'dokan' ); ?>
         </label>

         <div class="dokan-w5">
             <select class="dokan-form-control" name="vendor_type" id="vendor_type">
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
         </div>
     </div>

     <!-- Shipping to -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Shipping country', 'dokan' ); ?>
         </label>
         <div class="dokan-w5">
                 <select class="dokan-form-control" name="vendor_shipping_to" id="vendor_shipping_to">
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
         </div>
     </div>

     <!-- Annual revenue -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Annual revenue', 'dokan' ); ?>
         </label>

         <div class="dokan-w5">
             <select class="dokan-form-control" name="vendor_revenue" id="vendor_revenue">
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
         </div>
     </div>

     <!-- Employees -->
     <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Employees', 'dokan' ); ?>
         </label>

         <div class="dokan-w5">
             <select class="dokan-form-control" name="vendor_employees" id="vendor_employees">
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
         </div>
     </div>

     <!-- Product range -->
    <div class="custom dokan-form-group">
         <label class="dokan-w3 dokan-control-label" for="setting_address">
             <?php _e( 'Product range', 'dokan' ); ?>
         </label>

        <div class="dokan-w5">
         <select class="dokan-form-control" name="vendor_product_range" id="vendor_product_range">
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
        $query_vars['offer'] = 'offer';
        return $query_vars;
    }
    add_filter( 'dokan_get_dashboard_nav', 'dokan_add_offer_menu' );
    function dokan_add_offer_menu( $urls ) {
        $urls['offer'] = array(
            'title' => __( 'Offers', 'dokan'),
            'icon'  => '<i class="fa fa-user"></i>',
            'url'   => dokan_get_navigation_url( 'offer' ),
            'pos'   => 51
        );
        return $urls;
    }

    /*
     *  Add actions to Offers
     *
     * @since 3.0.16
     * @package dokan
     *
     */

    //Main file
    add_action( 'dokan_load_custom_template', 'dokan_load_template' );
    function dokan_load_template( $query_vars ) {
        if ( isset( $query_vars['offer'] ) ) {
            require_once dirname( __FILE__ ). '/dokan/templates/offers/offers.php';
        }
    }

    //Header
    add_action('dokan_offer_header', 'render_header_offers');
    function render_header_offers(){
        require_once dirname( __FILE__ ). '/dokan/templates/offers/header.php';
    }

    //Filter(tabs)
    add_action('dokan_offer_filter', 'render_filter_offers');
    function render_filter_offers(){
        require_once dirname( __FILE__ ). '/dokan/templates/offers/status-filter.php';
    }

    //Content
    add_action('dokan_main_content', 'render_content_offers');
    function render_content_offers(){
        require_once dirname( __FILE__ ). '/dokan/templates/offers/content.php';
    }

    //Active Table
    add_action('dokan_active_offer_table', 'render_active_offer_table');
    function render_active_offer_table(){
        require_once dirname( __FILE__ ). '/dokan/templates/offers/active_offers.php';
    }

    //Active Table
    add_action('dokan_my_offer_table', 'render_my_offer_table');
    function render_my_offer_table(){
        require_once dirname( __FILE__ ). '/dokan/templates/offers/my_offers.php';
    }

    //Add - Create new offer template
    add_filter( 'dokan_query_var_filter', 'dokan_load_document_menu_offer' );
    function dokan_load_document_menu_offer( $query_vars ) {
        $query_vars['new-offer'] = 'new-offer';
        return $query_vars;
    }

    add_action( 'dokan_load_custom_template', 'dokan_load_template_offer' );
    function dokan_load_template_offer( $query_vars ) {
        if ( isset( $query_vars['new-offer'] ) ) {
            require_once dirname( __FILE__ ). '/dokan/templates/offers/new-offer.php';
        }
    }

    //Add - Edit offer template
    add_filter( 'dokan_query_var_filter', 'dokan_load_document_menu_edit_offer' );
    function dokan_load_document_menu_edit_offer( $query_vars ) {
        $query_vars['edit-offer'] = 'edit-offer';
        return $query_vars;
    }

//    add_action( 'dokan_load_custom_template', 'dokan_load_template_edit_offer' );
//    function dokan_load_template_edit_offer( $query_vars ) {
//        if ( isset( $query_vars['edit-offer'] ) ) {
//            require_once dirname( __FILE__ ). '/dokan/templates/offers/edit-offer.php';
//        }
//    }

    //Add - My offers
    add_filter( 'dokan_query_var_filter', 'dokan_load_document_menu_my_offers' );
    function dokan_load_document_menu_my_offers( $query_vars ) {
        $query_vars['/my-offers'] = 'my-offers';
        return $query_vars;
    }

    add_action( 'dokan_load_custom_template', 'dokan_load_template_my_offers' );
    function dokan_load_template_my_offers( $query_vars ) {
        if ( isset( $query_vars['my-offers'] ) ) {
            require_once dirname( __FILE__ ). '/dokan/templates/offers/my_offers.php';
        }
    }


/**
 * Validation add for product cover image
 *
 * @param array $errors
 * @return array $errors
 */
function dokan_can_add_product_validation_customized( $errors ) {
    $postdata = wp_unslash( $_POST );
    $featured_image = absint( sanitize_text_field( $postdata['feat_image_id'] ) );
    $_regular_price = absint( sanitize_text_field( $postdata['_regular_price'] ) );
    if ( empty( $featured_image ) && ! in_array( 'Please upload a product cover image' , $errors ) ) {
        $errors[] = 'Please upload a product cover image';
    }
    if ( empty( $_regular_price ) && ! in_array( 'Please insert product price' , $errors ) ) {
        $errors[] = 'Please insert product price';
    }
    return $errors;
}
add_filter( 'dokan_can_add_product', 'dokan_can_add_product_validation_customized', 35, 1 );
add_filter( 'dokan_can_edit_product', 'dokan_can_add_product_validation_customized', 35, 1 );
function dokan_new_product_popup_validation_customized( $errors, $data ) {
    if ( ! $data['_regular_price'] ) {
        return new WP_Error( 'no-price', __( 'Please insert product price', 'dokan-lite' ) );
    }
    if ( ! $data['feat_image_id'] ) {
        return new WP_Error( 'no-image', __( 'Please select AT LEAST ONE Picture', 'dokan-lite' ) );
    }
}
add_filter( 'dokan_new_product_popup_args', 'dokan_new_product_popup_validation_customized', 35, 2 );


/**
 * THIS IS AN EXAMPLE
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

