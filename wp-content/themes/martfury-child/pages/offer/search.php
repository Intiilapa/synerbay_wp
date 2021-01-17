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

defined( 'ABSPATH' ) || exit;
global $offers, $searchParameters;
// paginator attributes
global $rowPerPage, $currentPage, $allRow, $lastPage;

//$arr = [
//    'result: '. count($offers),
//    'row / page: '. $rowPerPage,
//    'current page: '.$currentPage,
//    'all offer: '.$allRow,
//    'last page: '.$lastPage,
//];
//var_dump($_GET);
//echo '<br>';
//var_dump($searchParameters);
//echo '<br>';
//echo implode('<br>', $arr);
//var_dump($offers);
//die();

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
?>
    <!-- Sidebar -->
    <aside id="primary-sidebar"
           class="widgets-area primary-sidebar col-md-3 col-sm-12 col-xs-12 <?php echo esc_attr('catalog-sidebar') ?>">
        <!-- Search form before sidebar -->
        <form action="/offers" method="get">
            <ul>
                <li class="dokan-form-group">
                    <label for="query">Product name</label>
                    <input type="text" name="query" value="<?php echo $searchParameters['query'] ? $searchParameters['query'] : ''; ?>" class="dokan-form-control">
                </li>
                <li class="dokan-form-group">
                    <label for="start-date">Offer start date</label>
                    <input type="date" name="start-date" value="<?php the_search_query(); ?>" class="dokan-form-control">
                </li>
                <li class="dokan-form-group">
                    <label for="end-date">Offer end date</label>
                    <input type="date" name="start-date" value="<?php the_search_query(); ?>" class="dokan-form-control">
                </li>
                <li>
                    <label for="default_price">Default price</label>
                    <input type="number" step="1" value="<?php the_search_query(); ?>" class="dokan-form-control dokan-product" name="default_price" placeholder="" id="input_default_price" value="0">
                </li>
            </ul>
            <input type="hidden" value="post" name="post_type" id="post_type" />
            <input type="submit" name="search" value="Search" class="dokan-btn dokan-btn-theme">
        </form>
        <!-- Start sidebar -->
        <?php if (is_active_sidebar('synerbay_sidebar')) : ?>
            <?php dynamic_sidebar('synerbay_sidebar'); ?>
        <?php endif; ?>
    </aside>
    <!-- Main content -->
    <div id="primary" class="content-area col-md-9 col-sm-12 col-xs-12 ?>">
    <header class="woocommerce-products-header">
        <h1 class="woocommerce-products-header__title page-title">Offer search results (<?php echo count($offers) ?>)</h1>
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

} else {
    /**
     * Hook: woocommerce_no_products_found.
     *
     * @hooked wc_no_products_found - 10
     */
    do_action('woocommerce_no_products_found');
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');
get_footer('shop');
