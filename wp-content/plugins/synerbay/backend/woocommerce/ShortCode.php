<?php


namespace SynerBay\WooCommerce;


class ShortCode
{
    public function __construct()
    {
        add_shortcode('recent_offers', [$this, 'recentOffers']);
        add_shortcode('almost_finished_offers', [$this, 'almostFinishedOffers']);
    }

    /**
     * Új ajánlatok
     *
     * @param $attributes
     */
    public function recentOffers($attributes)
    {
        extract(shortcode_atts($attributes, array(
            'per_page' 	=> '12',
            'columns' 	=> '4',
            'orderby' => 'id',
            'order' => 'desc'
        )));


        $offerSearch = new OfferSearch(['recent_offers' => true, 'order' => ['columnName' => $orderby, 'direction' => $order]]);

        $offerIds = $offerSearch->search();

        if (count($offerIds)) {

            shuffle($offerIds);

            $offerIds = array_slice($offerIds, 0, $per_page);

            $offerModule = new Offer();
            $offers = $offerModule->prepareOffers(array_values($offerIds), true, true, true, true);

            woocommerce_product_loop_start();

            global $offer;
            global $post;
            foreach ($offers as $offer) {
                $post = get_post($offer['product']['ID']);
                wc_get_template_part( 'content', 'offer' );

                $offer = [];
            }

            woocommerce_product_loop_end();
        } else {
            wc_get_template( 'loop/no-products-found.php' );
        }

        wp_reset_postdata();
    }


    /**
     * Nemsokára végetérő ajánlatok
     *
     * @param $attributes
     */
    public function almostFinishedOffers($attributes)
    {
        extract(shortcode_atts($attributes, array(
            'per_page' 	=> '12',
            'columns' 	=> '4',
            'orderby' => 'offer_end_date',
            'order' => 'desc'
        )));


        $offerSearch = new OfferSearch(['recent_offers' => true, 'order' => ['columnName' => $orderby, 'direction' => $order]]);

        $offerIds = $offerSearch->search();

        if (count($offerIds)) {

            shuffle($offerIds);

            $offerIds = array_slice($offerIds, 0, $per_page);

            $offerModule = new Offer();
            $offers = $offerModule->prepareOffers(array_values($offerIds), true, true, true, true);

            woocommerce_product_loop_start();

            global $offer;
            global $post;
            foreach ($offers as $offer) {
                $post = get_post($offer['product']['ID']);
                wc_get_template_part( 'content', 'offer' );

                $offer = [];
            }

            woocommerce_product_loop_end();
        } else {
            wc_get_template( 'loop/no-products-found.php' );
        }

        wp_reset_postdata();
    }
}