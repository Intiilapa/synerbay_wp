<?php
/**
 * The Template for displaying all reviews.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

// user adat
global $currentUser;

// user offerei és a paginációhoz minden
global $offers, $searchParameters, $rowPerPage, $currentPage, $allRow, $lastPage;

// TODO Remco itt van minden adat, Fontos: a lapozó get-es legyen és page legyen a neve, pl.: ?page=2
//print '<pre>';
//var_dump(count($offers));
//var_dump($searchParameters);
//var_dump($rowPerPage);
//var_dump($currentPage);
//var_dump($allRow);
//var_dump($lastPage);
//die;

$store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info   = $store_user->get_shop_info();
$map_location = $store_user->get_location();
$layout       = get_theme_mod( 'store_layout', 'left' );

get_header( 'shop' );
?>

<?php do_action( 'woocommerce_before_main_content' ); ?>

<div class="dokan-store-wrap layout-<?php echo esc_attr( $layout ); ?>">

    <?php if ( 'left' === $layout ) { ?>
        <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $currentUser, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
    <?php } ?>

    <div id="dokan-primary" class="dokan-single-store">
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
