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
global $offers, $searchParameters;
// paginator attributes
global $rowPerPage, $currentPage, $allRow, $lastPage;

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
        <form action="/offers" method="get">
            <ul class="offer-search-sidebar">
                <input type="hidden" name="offer-site-search" value="<?php echo $searchParameters['offer-site-search']; ?>">
                <li class="dokan-form-group">
                    <label for="query">Product name</label>
                    <input type="text" name="query" value="<?php echo isset($searchParameters['query']) ? $searchParameters['query'] : ''; ?>" class="dokan-form-control">
                </li>
                <?php do_action('synerbay_getOfferSearchShippingToSelect', isset($searchParameters['shipping_to']) ? $searchParameters['shipping_to'] : false);?>
                <?php do_action('synerbay_getOfferSearchProductCategorySelect', isset($searchParameters['category_id']) ? $searchParameters['category_id'] : false);?>
                <?php do_action('synerbay_getOfferSearchCurrencySelect', isset($searchParameters['cur']) ? $searchParameters['cur'] : false);?>
                <?php do_action('synerbay_getOfferSearchTransportParitySelect', isset($searchParameters['transport_parity']) ? $searchParameters['transport_parity'] : false);?>
                <?php do_action('synerbay_getOfferSearchVendorSelect', isset($searchParameters['user_id']) ? $searchParameters['user_id'] : false);?>
                <!--<li>
                    <label for="default_price">Default price (ex: from $10 - to $20)</label>
                    <input type="number" step="1" value="<?php echo $searchParameters['default_price'] ? $searchParameters['default_price'] : ''; ?>" class="dokan-form-control dokan-product" name="default_price" placeholder="" id="input_default_price" value="0">
                </li>-->
                 <li>
                    <label for="default_price_from">Min. Offer Price</label>
                    <input type="number" step="1" value="<?php echo $searchParameters['default_price_from'] ? $searchParameters['default_price_from'] : ''; ?>" class="dokan-form-control dokan-product" name="default_price_from" placeholder="" id="input_default_price_from" value="0">
                </li>
                 <li>
                    <label for="default_price_to">Max. Offer Price</label>
                    <input type="number" step="1" value="<?php echo $searchParameters['default_price_to'] ? $searchParameters['default_price_to'] : ''; ?>" class="dokan-form-control dokan-product" name="default_price_to" placeholder="" id="input_default_price_to" value="0">
                </li>
                <li class="dokan-form-group">
                    <label for="offer-start-date">Offer start date</label>
                    <input type="date" name="offer-start-date" value="<?php echo $searchParameters['offer-start-date'] ? $searchParameters['offer-start-date'] : ''; ?>" class="dokan-form-control">
                </li>
                <li class="dokan-form-group">
                    <label for="offer-start-date-from">Offer start date (from)</label>
                    <input type="date" <?php echo $safariDateInputFix; ?> name="offer-start-date-from" value="<?php echo $searchParameters['offer-start-date-from'] ? $searchParameters['offer-start-date-from'] : ''; ?>" class="dokan-form-control">
                </li>
                <li class="dokan-form-group">
                    <label for="offer-start-date-to">Offer start date (to)</label>
                    <input type="date" <?php echo $safariDateInputFix; ?> name="offer-start-date-to" value="<?php echo $searchParameters['offer-start-date-to'] ? $searchParameters['offer-start-date-to'] : ''; ?>" class="dokan-form-control">
                </li>
                <li class="dokan-form-group">
                    <label for="offer-end-date">Offer end date</label>
                    <input type="date" <?php echo $safariDateInputFix; ?> name="offer-end-date" value="<?php echo $searchParameters['offer-end-date'] ? $searchParameters['offer-end-date'] : ''; ?>" class="dokan-form-control">
                </li>
                <li class="dokan-form-group">
                    <label for="offer-end-date-from">Offer end date (from)</label>
                    <input type="date" <?php echo $safariDateInputFix; ?> name="offer-end-date-from" value="<?php echo $searchParameters['offer-end-date-from'] ? $searchParameters['offer-end-date-from'] : ''; ?>" class="dokan-form-control">
                </li>
                <li class="dokan-form-group">
                    <label for="offer-end-date-to">Offer end date (to)</label>
                    <input type="date" <?php echo $safariDateInputFix; ?> name="offer-end-date-to" value="<?php echo $searchParameters['offer-end-date-to'] ? $searchParameters['offer-end-date-to'] : ''; ?>" class="dokan-form-control">
                </li>
                <li class="dokan-form-group">
                    <label for="delivery-date">Delivery date</label>
                    <input type="date" <?php echo $safariDateInputFix; ?> name="delivery-date" value="<?php echo $searchParameters['delivery-date'] ? $searchParameters['delivery-date'] : ''; ?>" class="dokan-form-control">
                </li>
                <li class="dokan-form-group">
                    <label for="delivery-date-from">Delivery date (from)</label>
                    <input type="date" <?php echo $safariDateInputFix; ?> name="delivery-date-from" value="<?php echo $searchParameters['delivery-date-from'] ? $searchParameters['delivery-date-from'] : ''; ?>" class="dokan-form-control">
                </li>
                <li class="dokan-form-group">
                    <label for="delivery-date-to">Delivery date (to)</label>
                    <input type="date" <?php echo $safariDateInputFix; ?> name="delivery-date-to" value="<?php echo $searchParameters['delivery-date-to'] ? $searchParameters['delivery-date-to'] : ''; ?>" class="dokan-form-control">
                </li>
                <li>
                    <input type="submit" name="clear" value="Clear fields" class="dokan-btn dokan-btn-theme">
                    <input type="submit" name="search" value="Search" class="dokan-btn dokan-btn-theme">
                </li>
            </ul>

        </form>
    </aside>

    <!-- Main content -->
    <div id="primary" class="content-area col-md-9 col-sm-12 col-xs-12 ?>">
    <header class="woocommerce-products-header">
        <h1 class="woocommerce-products-header__title page-title">Offer search results</h1>
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

if (count($offers)) {

    woocommerce_product_loop_start();

    global $offer;
    global $post;
    foreach ($offers as $offer) {
        $post = get_post($offer['product']['ID']);
        wc_get_template_part('content', 'offer');

        $offer = [];
    }

    woocommerce_product_loop_end();

    // pagination
    $base_url  = RouteHelper::generateRoute('offer_listing');

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
