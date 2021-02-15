<?php


namespace SynerBay\WooCommerce;

use SynerBay\Module\Offer;
use SynerBay\Search\OfferSearch;
use WC_Shortcodes;

class ShortCode extends WC_Shortcodes
{
    public function __construct()
    {
        add_shortcode('recent_offers', [$this, 'recentOffers']);
        add_shortcode('almost_finished_offers', [$this, 'almostFinishedOffers']);
        add_shortcode('almost_finished_offers_quantity', [$this, 'almostFinishedOffersQuantity']);
    }

    /**
     * Új ajánlatok (főoldal)
     *
     * @param $attributes
     */
    public function recentOffers($attributes)
    {
        extract(shortcode_atts($attributes, [
            'per_page' => '16',
            'columns'  => '5',
            'orderby'  => 'id',
            'order'    => 'desc',
        ]));


        $offerSearch = new OfferSearch([
            'recent_offers' => true,
            'order'         => ['columnName' => $orderby, 'direction' => $order],
        ]);

        $offerIds = $offerSearch->paginate(2 * (int)$per_page, 1);

        if (count($offerIds)) {

            shuffle($offerIds);

            $offerIds = array_slice($offerIds, 0, $per_page);

            /** @var Offer $offerModule */
            $offerModule = new Offer();
            $offers = $offerModule->prepareOffers(array_values($offerIds), true, true, true, true);

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
            wc_get_template('loop/no-products-found.php');
        }

        wp_reset_postdata();
    }


    /**
     * Nemsokára végetérő ajánlatok (főoldal)
     *
     * @param $attributes
     */
    public function almostFinishedOffers($attributes)
    {
        extract(shortcode_atts($attributes, [
            'per_page' => '16',
            'columns'  => '5',
            'orderby'  => 'offer_end_date',
            'order'    => 'asc',
        ]));

        $offerSearch = new OfferSearch([
            'recent_offers' => true,
            'order'         => ['columnName' => $orderby, 'direction' => $order],
        ]);

        $offerIds = $offerSearch->paginate($per_page, 1);

        if (count($offerIds)) {

            $offers = (new Offer())->prepareOffers(array_values($offerIds), true, true, true, true);

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
            wc_get_template('loop/no-products-found.php');
        }

        wp_reset_postdata();
    }

    /**
     * Nemsokára végetérő ajánlatok mennyiseg alapjan (főoldal)
     *
     * @param $attributes
     */
    public function almostFinishedOffersQuantity($attributes)
    {
        extract(shortcode_atts($attributes, [
            'per_page' => '16',
            'columns'  => '5',
            'orderby'  => 'current_quantity',
            'order'    => 'desc',
        ]));

        $offerSearch = new OfferSearch([
            'recent_offers' => true,
            'order'         => ['columnName' => $orderby, 'direction' => $order],
        ]);

        $offerIds = $offerSearch->paginate($per_page, 1);

        if (count($offerIds)) {

            $offers = (new Offer())->prepareOffers(array_values($offerIds), true, true, true, true);

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
            wc_get_template('loop/no-products-found.php');
        }

        wp_reset_postdata();
    }
}