<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

use SynerBay\Helper\RouteHelper;defined( 'ABSPATH' ) || exit;
global $wp_query, $sellers, $searchParameters;
// paginator attributes
global $rowPerPage, $currentPage, $allRow, $lastPage;

$wp_query->is_singular = true;
$wp_query->is_single = true;
$wp_query->is_404 = false;
status_header( 200 );


$safariDateInputFix = ' placeholder="dd/mm/yyyy" pattern="(^(((0[1-9]|1[0-9]|2[0-8])[\/](0[1-9]|1[012]))|((29|30|31)[\/](0[13578]|1[02]))|((29|30)[\/](0[4,6,9]|11)))[\/](19|[2-9][0-9])\d\d$)|(^29[\/]02[\/](19|[2-9][0-9])(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)$)"';

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
?>

<script>
   function showFilter() {
        var filter = document.getElementById("primary-sidebar");
        if (filter.style.display === "block") {
            filter.style.display = "none";
        } else {
            filter.style.display = "block";
        }
    }
</script>

<div class="dropdown-search">
    <button class="filter-btn" onclick="showFilter()">Toggle Offer Filters</button>
</div>
    <!-- Sidebar -->
    <aside id="primary-sidebar"
           class="widgets-area primary-sidebar col-md-3 col-sm-12 col-xs-12 offer-search-content <?php echo esc_attr('catalog-sidebar') ?>">
        <!-- Search form before sidebar -->
        <form id="store-search" action="/stores" method="get">
            <ul class="offer-search-sidebar">
                <input type="hidden" name="store-site-search" value="<?php echo $searchParameters['store-site-search']; ?>">
                <li class="dokan-form-group">
                    <label for="query">Store name</label>
                    <input type="text" name="query" value="<?php echo isset($searchParameters['query']) ? $searchParameters['query'] : ''; ?>" class="dokan-form-control">
                </li>
                <?php do_action('synerbay_getVendorSearchIndustrySelect', isset($searchParameters['industry']) ? $searchParameters['industry'] : false);?>
                <?php do_action('synerbay_getVendorSearchCompanyTypeSelect', isset($searchParameters['company_type']) ? $searchParameters['company_type'] : false);?>
                <?php do_action('synerbay_getVendorSearchDeliveryDestinationsSelect', isset($searchParameters['shipping_to']) ? $searchParameters['shipping_to'] : false);?>
                <?php do_action('synerbay_getVendorSearchAnnualRevenueSelect', isset($searchParameters['annual_revenue']) ? $searchParameters['annual_revenue'] : false);?>
                <?php do_action('synerbay_getVendorSearchEmployeesSelect', isset($searchParameters['employees']) ? $searchParameters['employees'] : false);?>
                <?php do_action('synerbay_getVendorSearchProductRangeSelect', isset($searchParameters['product_range']) ? $searchParameters['product_range'] : false);?>
                <?php /* do_action('synerbay_getVendorSearchRatingSelect', isset($searchParameters['rating']) ? $searchParameters['rating'] : false);*/?>
                <li>
                    <input type="submit" name="search" value="Search" class="dokan-btn dokan-btn-theme">
                    <input type="submit" name="clear" value="Clear fields" class="dokan-btn dokan-btn-theme">
                </li>
            </ul>

        </form>
    </aside>

    <!-- Main content -->
    <div id="primary" class="content-area col-md-9 col-sm-12 col-xs-12 ?>">
    <header class="woocommerce-products-header">
        <h1 class="woocommerce-products-header__title page-title">Store search results</h1>
        <?php
        /**
         * Hook: woocommerce_archive_description.
         *
         * @hooked woocommerce_taxonomy_archive_description - 10
         * @hooked woocommerce_product_archive_description - 10
         */
        do_action('woocommerce_archive_description');
        ?>
    </header>

<?php

if (count($sellers)) {

        $attributes = [
            'columns'  => '5',
            'orderby'  => 'ID',
            'order'    => 'desc',
            'per_page' => 10,
            'search'   => 'yes',
            'per_row'  => 3,
            'featured' => 'no',
        ];

        ob_start();

        do_action( 'dokan_before_seller_listing_loop', $sellers );

        $template_args = array(
            'sellers'         => $sellers,
            'image_size'      => 'full',
        );

        dokan_get_template_part( 'store-lists-loop', false, $template_args );

        /**
         * Action hook after finishing seller listing loop
         *
         * @since 2.8.6
         *
         * @var array $sellers
         */
        do_action( 'dokan_after_seller_listing_loop', $sellers );
        $content = ob_get_clean();

        echo apply_filters( 'dokan_seller_listing', $content, $attributes );

    // pagination
    $base_url  = RouteHelper::generateRoute('store_listing');

    if ( $lastPage > 1 ) {
        echo '<nav class="woocommerce-pagination">';
        $page_links = paginate_links( [
            'current'   => $currentPage,
            'total'     => $lastPage,
            'base'      => $base_url . '%_%',
            'format'    => '?page=%#%',
            'add_args'  => false,
            'type'      => 'array',
        ] );

        echo "<ul class='pagination'>\n\t<li>";
        echo join( "</li>\n\t<li>", $page_links );
        echo "</li>\n</ul>\n";
        echo '</nav>';
    }

} else {?>
     <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearch'); ?></p>
  <?php }

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');
get_footer('shop');
