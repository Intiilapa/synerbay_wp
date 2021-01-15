<?php

/**
 * Class for all WooCommerce template modification
 *
 * @version 1.0
 */
class Martfury_Child_WooCommerce
{
    function __construct()
    {
        // Add product thumbnail
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');
        add_action('woocommerce_before_shop_loop_offer_title', array($this, 'product_content_offer_thumbnail'));
    }

    /**
     * WooCommerce Loop Offer Content Thumbs
     *
     * @return string
     * @since  1.0
     *
     */
    function product_content_offer_thumbnail() {
        global $product, $post, $offer;

        printf( '<div class="mf-product-thumbnail">' );

        printf('<a href="%s" target="_blank">', esc_url($offer['url']));

        $image_size = 'shop_catalog';
        if ( has_post_thumbnail() ) {
            $thumbnail_class   = apply_filters( 'martfury_product_thumbnail_classes', '' );
            $post_thumbnail_id = get_post_thumbnail_id( $post );
            echo martfury_get_image_html( $post_thumbnail_id, $image_size, $thumbnail_class );

        } elseif ( function_exists( 'woocommerce_get_product_thumbnail' ) ) {
            echo woocommerce_get_product_thumbnail();
        }

        do_action( 'martfury_after_product_loop_thumbnail' );

        echo '</a>';

        do_action( 'martfury_after_product_loop_thumbnail_link' );


        echo '</div>';
        wc_get_template('loop/offer-badge.php');
        wc_get_template('loop/offer-progress.php');
        wc_get_template('loop/offer-countdown.php');
    }
}