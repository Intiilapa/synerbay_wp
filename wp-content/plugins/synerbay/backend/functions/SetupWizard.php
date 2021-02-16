<?php
namespace SynerBay\Functions\Dokan\Vendor\Wizard;

use WC_Countries;
use WeDevs\Dokan\Vendor\SetupWizard as DokanVendorSetupWizard;

class SetupWizard extends DokanVendorSetupWizard {

    /**
     * Introduction step.
     */
    public function dokan_setup_introduction() {
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

        $store_ppp       = isset( $store_info['store_ppp'] ) ? esc_attr( $store_info['store_ppp'] ) : 12;
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

        // fix role and permissions
        $vendor = dokan_get_vendor($this->store_id);
        $vendor->update_meta( 'dokan_enable_selling', 'yes' );
        $vendor->update_meta( 'dokan_feature_seller', 'yes' );
        $vendor->update_meta( 'dokan_publishing', 'yes' );
        // role beállítása
        $vendor->set_role('synerbay_user');

        delete_user_meta( $this->store_id, '_dokan_email_pending_verification' );
        delete_user_meta( $this->store_id, '_dokan_email_verification_key' );

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
