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

$arr = [
    'result: '. count($offers),
    'row / page: '. $rowPerPage,
    'current page: '.$currentPage,
    'all offer: '.$allRow,
    'last page: '.$lastPage,
];
var_dump($_GET);
echo '<br>';
var_dump($searchParameters);
echo '<br>';
echo implode('<br>', $arr);

get_header( 'shop' );
//var_dump($searchParameters);
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
        <?php if (is_active_sidebar('synerbay_sidebar')) : ?>
            <?php dynamic_sidebar('synerbay_sidebar'); ?>
        <?php endif; ?>
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
if (woocommerce_product_loop()) {

    /**
     * Hook: woocommerce_before_shop_loop.
     *
     * @hooked woocommerce_output_all_notices - 10
     * @hooked woocommerce_result_count - 20
     * @hooked woocommerce_catalog_ordering - 30
     */
    do_action('woocommerce_before_shop_loop');

    woocommerce_product_loop_start();

    if (wc_get_loop_prop('total')) {
        while (have_posts()) {
            the_post();

            /**
             * Hook: woocommerce_shop_loop.
             */
            do_action('woocommerce_shop_loop');

            wc_get_template_part('content', 'product');
        }
    }

    woocommerce_product_loop_end();

    /**
     * Hook: woocommerce_after_shop_loop.
     *
     * @hooked woocommerce_pagination - 10
     */
    do_action('woocommerce_after_shop_loop');
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
