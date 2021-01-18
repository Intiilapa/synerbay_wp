<?php

namespace SynerBay\Helper;

use SynerBay\Repository\VendorRepository;
use WP_Term;

class SynerBayDataHelper
{
    public static function getMaterialTypes()
    {
        return ArrayHelper::reKeyBySlugFromValue([
            '-',
            'Wood',
            'Metal',
            'Plastic',
            'Textil',
            'Latex',
            'PVC',
            'Silicone',
            'Leather',
            'Paper',
            'Fiber',
            'Glass',
            'Chemical',
            'Composite-material',
            'Mineral',
            'Stone',
            'Concrete',
            'Plaster',
            'Ceramic',
            'Rubber',
            'Foam',
            'Semiconductor',
            'Rare-earths',
        ]);
    }

    public static function getUnitTypes()
    {
        return ArrayHelper::reKeyBySlugFromValue([
            'piece',
            'mm',
            'cm',
            'm',
            'km',
            'ft',
            'in',
            'mg',
            'g',
            'dg',
            'kg',
            'ml',
            'cl',
            'dl',
            'l',
            'mm2',
            'cm2',
            'm2',
            'km2',
        ]);
    }

    public static function getOfferTransportParityTypes()
    {
        return [
            'exw' => 'EXW – Ex Works (named place of delivery)',
            'fca' => 'FCA – Free Carrier (named place of delivery)',
            'cpt' => 'CPT – Carriage Paid To (named place of destination)',
            'cip' => 'CIP – Carriage and Insurance Paid to (named place of destination)',
            'dpu' => 'DPU – Delivered At Place Unloaded (named place of destination)',
            'dap' => 'DAP – Delivered At Place (named place of destination)',
            'ddp' => 'DDP – Delivered Duty Paid (named place of destination)',
            'fas' => 'FAS – Free Alongside Ship (named port of shipment)',
            'fob' => 'FOB – Free on Board (named port of shipment)',
            'cfr' => 'CFR – Cost and Freight (named port of destination)',
            'cif' => 'CIF – Cost, Insurance & Freight (named port of destination)',
            'daf' => 'DAF – Delivered at Frontier (named place of delivery)',
            'dat' => 'DAT – Delivered at Terminal',
            'des' => 'DES – Delivered Ex Ship',
            'deq' => 'DEQ – Delivered Ex Quay (named port of delivery)',
            'ddu' => 'DDU – Delivered Duty Unpaid (named place of destination)',
        ];
    }

    public static function setupDeliveryDestinationsForOfferData(array $deliveryDestinationsSlugs)
    {
        $ret = [];
        $destinations = self::getDeliveryDestinationsForOffer();

        foreach ($deliveryDestinationsSlugs as $slug) {
            if (array_key_exists($slug, $destinations)) {
                $ret[] = $destinations[$slug];
            }
        }

        return $ret;
    }

    public static function getDeliveryDestinationsForOffer()
    {
        return ArrayHelper::reKeyBySlugFromValue([
            'Worldwide',
            'Africa',
            'North America',
            'South America',
            'Europe',
            'Asia',
            'Middle East',
            'Ocean Pacific',
        ]);
    }

    public static function getYesNo()
    {
        return ArrayHelper::reKeyBySlugFromValue([
            'Yes',
            'No',
        ]);
    }

    // a child/functions.php-ban van hgsználva és a merge elhalt sokszor
    public static function getDeliveryDestinations()
    {
        return [
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
        ];
    }

    public static function getCategoriesFromDB()
    {
        $orderby = 'name';
        $order = 'asc';
        $hide_empty = true;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
        );

        return get_terms( 'product_cat', $cat_args );
    }

    public static function getCategoriesFromDBToSelect()
    {
        $ret = [];
        $categories = self::getCategoriesFromDB();
        if (count($categories)) {
            /** @var WP_Term $category */
            foreach ($categories as $category) {
                $ret[$category->term_id] = $category->name;
            }
        }

        return $ret;
    }

    public static function getActiveVendorsForOfferSearch()
    {
        $ret = [];
        $vendors = (new VendorRepository())->getActiveVendorsForSelect();

        if (count($vendors)) {
            foreach ($vendors as $vendor) {
                $ret[$vendor['user_id']] = $vendor['store_name'];
            }
        }

        return $ret;
    }
}