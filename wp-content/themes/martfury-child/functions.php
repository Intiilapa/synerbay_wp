<?php

use SynerBay\Forms\CreateProduct as CreateProductForm;
use SynerBay\Forms\Validators\Required as RequiredValidator;

add_action( 'wp_enqueue_scripts', 'martfury_child_enqueue_scripts', 20 );
function martfury_child_enqueue_scripts() {
    wp_enqueue_style( 'martfury-child-style', get_stylesheet_uri() );
    wp_enqueue_style('child-style-custom', get_stylesheet_directory_uri() . '/custom.css');
	if ( is_rtl() ) {
		wp_enqueue_style( 'martfury-rtl', get_template_directory_uri() . '/rtl.css', array(), '20180105' );
	}

}

// include parent classes ...
//function myThemeIncludes() {
//    require_once __DIR__ . '/inc/frontend/woocommerce.php';
//
//    new Martfury_Child_WooCommerce();
//}
//
//add_action( 'after_setup_theme', 'myThemeIncludes' );

// end include parent classes

add_action('wp_footer', 'custom_footer_actions');
function custom_footer_actions(){
    do_action('synerbay_loader');
    do_action('synerbay_loginModal');
};

add_action('wp_head', 'custom_header_actions');
function custom_header_actions(){
    //do_action('synerbay_synerBayInviteButton');
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
    <?php if ( isset( $store_info['vendor_vat'] ) && !empty( $store_info['vendor_vat'] ) ) { ?>
       <li>
            <i class="fa fa-legal"></i>
            <a href="<?php echo esc_html( $store_info['vendor_vat'] ); ?>"><?php echo esc_html( $store_info['vendor_vat'] ); ?></a>
       </li>
    <?php } ?>

    <?php if ( isset( $store_info['vendor_type'] ) && !empty( $store_info['vendor_type'] ) ) { ?>
        <li>
            <i class="fa fa-globe"></i>
            <a style="text-transform: capitalize" href="<?php echo esc_html( $store_info['vendor_type'] ); ?>"><?php echo esc_html( $store_info['vendor_type'] ); ?></a>
        </li>
    <?php } ?>

    <?php if ( isset( $store_info['vendor_industry'] ) && !empty( $store_info['vendor_industry'] ) ) { ?>
        <li>
            <i class="fa fa-industry"></i>
            <a style="text-transform: capitalize" href="<?php echo esc_html( $store_info['vendor_industry'] ); ?>"><?php echo esc_html( $store_info['vendor_industry'] ); ?></a>
        </li>
    <?php } ?>

    <?php if ( isset( $store_info['vendor_shipping_to'] ) && !empty( $store_info['vendor_shipping_to'] ) ) { ?>
        <li>
            <i class="fa fa-map-o"></i>
            <a style="text-transform: capitalize" href="<?php echo esc_html( $store_info['vendor_shipping_to'] ); ?>"><?php echo esc_html( $store_info['vendor_shipping_to'] ); ?></a>
        </li>
    <?php } ?>

    <?php if ( isset( $store_info['vendor_revenue'] ) && !empty( $store_info['vendor_revenue'] ) ) { ?>
        <li>
            <i class="fa fa-money"></i>
            <a style="text-transform: capitalize" href="<?php echo esc_html( $store_info['vendor_shipping_to'] ); ?>"><?php echo esc_html( $store_info['vendor_revenue'] ); ?></a>
        </li>
    <?php } ?>

    <?php if ( isset( $store_info['vendor_employees'] ) && !empty( $store_info['vendor_employees'] ) ) { ?>
        <li>
            <i class="fa fa-users"></i>
            <a style="text-transform: capitalize" href="<?php echo esc_html( $store_info['vendor_shipping_to'] ); ?>"><?php echo esc_html( $store_info['vendor_employees'] ); ?></a>
        </li>
    <?php } ?>

    <?php if ( isset( $store_info['vendor_product_range'] ) && !empty( $store_info['vendor_product_range'] ) ) { ?>
        <li>
            <i class="fa fa-sliders"></i>
            <a style="text-transform: capitalize" href="<?php echo esc_html( $store_info['vendor_product_range'] ); ?>"><?php echo esc_html( $store_info['vendor_product_range'] ); ?></a>
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
    add_filter( 'dokan_query_var_filter', 'dokan_load_offer_menu' );
    function dokan_load_offer_menu( $query_vars ) {
        $query_vars['offer'] = 'offer';
        return $query_vars;
    }

    add_filter( 'dokan_get_dashboard_nav', 'dokan_add_offer_menu' );
    function dokan_add_offer_menu( $urls ) {
        $urls['offer'] = array(
            'title' => __( 'Offers', 'dokan'),
            'icon'  => '<i class="fa fa-bookmark"></i>',
            'url'   => dokan_get_navigation_url( 'offer' ),
            'pos'   => 51
        );
        return $urls;
    }

    /*
     *  Add actions to Offers
     *  Assign templates
     * @since 3.0.16
     * @package dokan
     *
     */

    //Add main offer page
    add_action( 'dokan_load_custom_template', 'dokan_load_template' );
    function dokan_load_template( $query_vars ) {
        if ( isset( $query_vars['offer'] ) ) {
            require_once dirname( __FILE__ ). '/dokan/templates/offers/offers.php';
        }
    }

    //Add header
    add_action('dokan_offer_header', 'render_header_offers');
    function render_header_offers(){
        require_once dirname( __FILE__ ). '/dokan/templates/offers/header.php';
    }

    //Add tabs
    add_action('dokan_offer_filter', 'render_filter_offers');
    function render_filter_offers(){
        require_once dirname( __FILE__ ). '/dokan/templates/offers/status-filter.php';
    }

    //Add basic template structure
    add_action('dokan_main_content', 'render_content_offers');
    function render_content_offers(){
        require_once dirname( __FILE__ ). '/dokan/templates/offers/content.php';
    }

    //Add Active offer
    add_action('dokan_active_offer_table', 'render_active_offer_table');
    function render_active_offer_table(){
        require_once dirname(__FILE__) . '/dokan/templates/offers/active-offers.php';
    }

    //Add My offers
    add_action('dokan_my_offer_table', 'render_my_offer_table');
    function render_my_offer_table(){
        require_once dirname(__FILE__) . '/dokan/templates/offers/my-offers.php';
    }

    //Add new offer
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

    //Add Edit offer
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

    //Add My offer
    add_filter( 'dokan_query_var_filter', 'dokan_load_document_menu_my_offers' );
    function dokan_load_document_menu_my_offers( $query_vars ) {
        $query_vars['my-offers'] = 'my-offers';
        return $query_vars;
    }

    add_action( 'dokan_load_custom_template', 'dokan_load_template_my_offers' );
    function dokan_load_template_my_offers( $query_vars ) {
        if ( isset( $query_vars['my-offers'] ) ) {
            require_once dirname(__FILE__) . '/dokan/templates/offers/my-offers.php';
        }
    }

    //Show offer applies
    add_filter( 'dokan_query_var_filter', 'dokan_load_document_menu_show_offers' );
    function dokan_load_document_menu_show_offers( $query_vars ) {
        $query_vars['show-offers'] = 'show-offers';
        return $query_vars;
    }

    add_action( 'dokan_load_custom_template', 'dokan_load_template_show_offers' );
    function dokan_load_template_show_offers( $query_vars ) {
        if ( isset( $query_vars['show-offers'] ) ) {
            require_once dirname(__FILE__) . '/dokan/templates/offers/show-offers.php';
        }
    }


/**
 * Validation for new product
 *
 * @param array $errors
 * @return array $errors
 */

function dokan_can_add_product_validation_customized( $errors ) {
    $postData = wp_unslash( $_POST );
    $featured_image = absint( sanitize_text_field( $postData['feat_image_id'] ) );
    //$_regular_price = absint( sanitize_text_field( $postData['_regular_price'] ) );

    if ( empty( $featured_image ) && ! in_array( 'Please upload a product cover image' , $errors ) ) {
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
            $error = $colMap[$col] .': '. $error;
        }

        $errors = array_merge($errors, $formErrors);
    }

    $validator = new RequiredValidator();

    return $errors;
}

add_filter( 'dokan_can_add_product', 'dokan_can_add_product_validation_customized', 35, 1 );
//add_filter( 'dokan_can_edit_product', 'dokan_can_add_product_validation_customized', 35, 1 );
function dokan_new_product_popup_validation_customized( $errors, $data ) {
    if ( ! $data['feat_image_id'] ) {
        return new WP_Error( 'no-image', __( 'Please select AT LEAST ONE Picture', 'dokan-lite' ) );
    }
    if ( ! $data['weight_unit'] ) {
        return new WP_Error( 'no-weight-unit', __( 'Please insert product weight unit', 'dokan-lite' ) );
    }
    if ( ! $data['material'] ) {
        return new WP_Error( 'no-material', __( 'Please insert product material', 'dokan-lite' ) );
    }
}

add_filter( 'dokan_new_product_popup_args', 'dokan_new_product_popup_validation_customized', 35, 2 );

/**
 * Add custom metafields to default product page
 */

add_action('woocommerce_single_product_summary','product_custom_details',13);
function product_custom_details(){
    global $product, $post, $rfqa;

    if ( empty( $product ) ) {
        return;
    }

    // init product rfqa to global
    do_action('synerbay_product_rfqs', $product->get_id());

    $weight_unit = get_post_meta( $product->get_id(), '_weight_unit', true );
    $weight_unit_type = get_post_meta( $product->get_id(), '_weight_unit_type', true );
    $material = get_post_meta( $product->get_id(), '_material', true );
    if ( ! empty( $weight_unit ) ) {
        ?><span class="custom_details"><?php echo esc_attr__( 'Unit: ', 'dokan-lite' ); ?><?php echo esc_attr( $weight_unit ); ?></span></br>
        <?php
    }
    if ( ! empty( $weight_unit_type ) ) {
        ?><span class="custom_details"><?php echo esc_attr__( 'Unit type: ', 'dokan-lite' ); ?><?php echo esc_attr( $weight_unit_type ); ?></span></br>
        <?php
    }
    if ( ! empty( $material ) ) {
        ?><span class="custom_details"><?php echo esc_attr__( 'Material: ', 'dokan-lite' ); ?><?php echo esc_attr( $material ); ?></span></br>
        <?php
    }

    //Show RFQ
    if ( is_product() && get_current_user_id() == $post->post_author) {
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
            // The new tab content
            //TODO -> itt mehet az RFQ tablazat/adatokat
            echo '<h2>RFQ</h2>';
            echo '
            <p>
                Andris:<br>Ide le kell írnunk, hogy amennyiben az adott termékből offer készül, akkor annak elindulásakor minden egyes igénylőt 
                kiértesítünk e-mail-ben, majd töröljük az igényét a termékről.<br>
                <strong>Információ</strong>: nem csak az igénylőket, hanem a személyes követőidet is kiértsítjük minden egyes esetben, amikor egy általad 
                készített ajánlat elindul, viszont ebben az esetben értelemszerűen nem kerülnek törlésre a követések.
            </p>';
            if (count($rfqs)) {
                print '<pre>';
                var_dump($rfqs);
            } else {
                echo '<br>Remco, kérdezd meg az Andristól, hogy mi legyen a szöveg, ha tök üres, nincs mit megjeleníteni!';
            }
        }
    }
    do_action('synerbay_product_buttons');
    echo '<hr>';
}

/**
 * Generate a string of random characters
 *
 * @param array $args   The arguments to use for this function
 * @return string|null  The random string generated by this function (only 'if($args['echo'] === false)')
 */
function my_random_string($args = array()){

    $defaults = array(  // Set some defaults for the function to use
        'characters'    => '0123456789',
        'length'        => 32,
        'before'        => '',
        'after'         => '',
        'echo'          => false
    );
    $args = wp_parse_args($args, $defaults);    // Parse the args passed by the user with the defualts to generate a final '$args' array

    if(absint($args['length']) < 1) // Ensure that the length is valid
        return;

    $characters_count = strlen($args['characters']);    // Check how many characters the random string is to be assembled from
    for($i = 0; $i <= $args['length']; $i++) :          // Generate a random character for each of '$args['length']'

        $start = mt_rand(0, $characters_count);
        $random_string.= substr($args['characters'], $start, 1);

    endfor;

    $random_string = $args['before'] . $random_string . $args['after']; // Add the before and after strings to the random string

    if($args['echo']) : // Check if the random string shoule be output or returned
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
function uniq_invite_code($user_id){

    $args = array(
        'length'    => 6,
        'before'    => date("Y")
    );
    $random_number = my_random_string($args);
    update_user_meta($user_id, '_invite_code', $random_number);

}

/*
 *
 * Completely remove cart from woocommerce
 *
 *
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );