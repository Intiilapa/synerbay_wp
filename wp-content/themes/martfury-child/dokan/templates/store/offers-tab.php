<?php
/**
 * The Template for displaying all reviews.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */
die('körte');
// user adat
global $currentUser, $store_user, $wp_query;

// user offerei és a paginációhoz minden
global $offers, $searchParameters, $rowPerPage, $currentPage, $allRow, $lastPage;

$wp_query->is_singular = true;
$wp_query->is_single = true;
$wp_query->is_404 = false;

// TODO Remco itt van minden adat, Fontos: a lapozó get-es legyen és page legyen a neve, pl.: ?page=2
//print '<pre>';
//var_dump(count($offers));
//var_dump($searchParameters);
//var_dump($rowPerPage);
//var_dump($currentPage);
//var_dump($allRow);
//var_dump($lastPage);
//die;
//dokan_get_template_part('store-header');
$store_user   = dokan()->vendor->get( $currentUser->ID );
//$store_user = get_userdata( get_query_var( 'author' ) );
//print '<pre>';var_dump($store_user);die;

$store_info   = dokan_get_store_info( $store_user->ID );
$map_location = isset( $store_info['location'] ) ? esc_attr( $store_info['location'] ) : '';
$layout       = get_theme_mod( 'store_layout', 'left' );

get_header( 'shop' );
?>

<?php do_action( 'woocommerce_before_main_content' ); ?>

<div class="dokan-store-wrap layout-<?php echo esc_attr( $layout ); ?>">

    <?php if ( 'left' === $layout ) { ?>
        <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $store_user, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
    <?php } ?>

    <div id="dokan-primary" class="dokan-single-store dokan-w8">
        <div id="dokan-content" class="store-page-wrap woocommerce" role="main">

            <?php dokan_get_template_part( 'store-header' ); ?>

            <?php do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info ); ?>

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
            //TODO $base_url miatt error.
            //$base_url  = RouteHelper::generateRoute('offers');

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

            } else { ?>

                <p class="dokan-info"><?php esc_html_e( 'No offers were found of this vendor!', 'dokan-lite' ); ?></p>

            <?php } ?>
        </div>

        </div><!-- #content .site-content -->
    </div><!-- #primary .content-area -->

    <?php if ( 'right' === $layout ) { ?>
        <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $currentUser, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
    <?php } ?>

</div><!-- .dokan-store-wrap -->

<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer(); ?>
