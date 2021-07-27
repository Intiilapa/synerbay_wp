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
        add_action('woocommerce_before_shop_loop_offerList_title', array($this, 'product_content_offerList_thumbnail'));
    }

    /**
     * WooCommerce Loop Offer Content Thumbs Normal view
     *
     * @return string
     * @since  1.0
     *
     */
    function product_content_offer_thumbnail() {
        global $product, $post, $offer;

        $offer_validity = date('Y-m-d', strtotime($offer['offer_end_date']));

        printf( '<div class="mf-product-thumbnail">' );

        wc_get_template('loop/offer-badge.php');

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

        echo '<b style="color: #0bb90b;font-size: 18px;"> '. $offer['summary']['formatted_actual_product_price'] . '</b> ' . ' <b style="text-decoration: line-through;color:grey;font-weight: 400;">' . $offer['summary']['next_price_step_discount_price_from_default'] . '</b> ' . ' <b style="color: red;font-weight: 400;">'. $offer['summary']['current_discount_percentage_from_default_price'] . '% off' . '</b>';
        echo '<br>';
        echo 'Lowest available price: ' .  '<b>'. '$' . $offer['summary']['max_price_step_price'] .'</b>';
        echo '<br>';
        echo '<b style="font-weight: 400;color:red;">' . 'Offer validity: ' . $offer_validity;
        echo '<br>';
        echo '<b style="font-weight: 400;">' . 'Sold: ' . $offer['summary']['actual_applicant_product_number'];
        echo '</div>';

        if (is_page("Network")) {
            printf('<a href="%s" class="button offer_list" rel="nofollow"><span class="add-to-cart-text">Read more</span></a>', esc_url($offer['url']));
        }

    }

    /**
     * WooCommerce Loop Offer Content Thumbs for List view
     *
     * @return string
     * @since  1.0
     *
     */
    function product_content_offerList_thumbnail() {
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
        echo '<div class="box">';
            wc_get_template('loop/offer-badge.php');
            wc_get_template('loop/offer-progress.php');
            wc_get_template('loop/offer-countdown.php');
        echo '</div>';
        if (is_page("Network")) {
            echo'<div class="buttons-offer-list">';
                do_action('synerbay_synerBayInviteShortcodeList', $offer['url']);
                printf('<a href="%s" class="button offer_list" rel="nofollow"><span class="add-to-cart-text">Read more</span></a>', esc_url($offer['url']));
            echo '</div>';
        }
    }
}