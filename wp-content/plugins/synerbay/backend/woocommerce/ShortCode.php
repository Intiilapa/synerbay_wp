<?php


namespace SynerBay\WooCommerce;

use SynerBay\Module\Offer;
use SynerBay\Repository\OfferRepository;
use SynerBay\Repository\ProductRepository;
use SynerBay\Repository\UserRepository;
use SynerBay\Repository\VendorRepository;
use SynerBay\Search\OfferSearch;
use WC_Shortcodes;

class ShortCode extends WC_Shortcodes
{
    public function __construct()
    {
        // marketplace shortcodes
        add_shortcode('recent_offers', [$this, 'recentOffers']);
        add_shortcode('almost_finished_offers', [$this, 'almostFinishedOffers']);
        add_shortcode('almost_finished_offers_quantity', [$this, 'almostFinishedOffersQuantity']);
        // network shortcodes
        add_shortcode('network_new_products', [$this, 'networkNewProducts']);
        add_shortcode('network_new_offers', [$this, 'networkNewOffers']);
        add_shortcode('network_offers_close_to_fulfillment_by_qty', [$this, 'networkOffersCloseToFulfillmentByQty']);
        add_shortcode('network_offers_close_to_fulfillment_by_time', [$this, 'networkOffersCloseToFulfillmentByTime']);
        add_shortcode('network_recommended_vendors_by_industry', [$this, 'networkRecommendedVendorsByIndustry']);
        add_shortcode('network_recommended_products', [$this, 'networkRecommendedProducts']);
        add_shortcode('network_recommended_offers', [$this, 'networkRecommendedOffers']);
    }

    /**
     * Új ajánlatok (főoldal)
     *
     * @param $attributes
     */
    public function recentOffers($attributes)
    {
        extract(shortcode_atts($attributes, [
            'per_page' => '12',
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
                //var_dump($post);
                wc_get_template_part('content', 'offer');

                $offer = [];
            }

            woocommerce_product_loop_end();
        } else {
            {?>
                <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearch'); ?></p>
            <?php }
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
            'per_page' => '12',
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
            {?>
                <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearch'); ?></p>
            <?php }
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
            'per_page' => '12',
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
            {?>
                <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearch'); ?></p>
            <?php }
        }

        wp_reset_postdata();
    }

    /**
     * @param $attributes
     * @return false|string
     */
    public function networkNewProducts($attributes)
    {
        extract(shortcode_atts([
            'per_page' => '12',
            'columns'  => '5',
            'orderby'  => 'ID',
            'order'    => 'desc',
        ], $attributes));

        $entityNotFound = true;
        $followedVendors = (new VendorRepository())->getFollowedUserIds(get_current_user_id());

        if (count($followedVendors)) {
            $productRepository = new ProductRepository();

            $searchParams = [
                'recent_offers' => true,
                'user_id' => array_column($followedVendors, 'vendor_id'),
                'order'         => ['columnName' => $orderby, 'direction' => $order],
            ];

            $products = $productRepository->paginate($searchParams, $per_page, 1);

            if (count($products)) {
                $entityNotFound = false;
                ob_start(); ?>
                <ul class="products">
                    <?php
                    global $product;

                    foreach ($products as $nwProduct) {
                        $product = wc_get_product($nwProduct['ID']);
                        wc_get_template_part('content', 'product');
                    }
                    ?>
                </ul>
                <?php
                wp_reset_postdata();
                return ob_get_clean();

            }
        }

        // üres a lista?
        if ($entityNotFound) {
            wc_get_template('loop/no-products-found.php');
        }

        wp_reset_postdata();
    }

    /**
     * @param $attributes
     */
    public function networkNewOffers($attributes)
    {
        extract(shortcode_atts([
            'per_page' => '12',
            'columns'  => '5',
            'orderby'  => 'id',
            'order'    => 'desc',
        ], $attributes));

        $entityNotFound = true;

        $followedVendors = (new VendorRepository())->getFollowedUserIds(get_current_user_id());

        if (count($followedVendors)) {
            $offerRepository = new OfferRepository();

            $offerSearchParams = [
                'user_id' => array_column($followedVendors, 'vendor_id'),
                'order'         => ['columnName' => $orderby, 'direction' => $order],
            ];

            $offers = $offerRepository->paginate($offerSearchParams, $per_page, 1);

            if (count($offers)) {
                $entityNotFound = false;
                $offers = (new Offer())->prepareOffers($offers, true, true, true, true);

                woocommerce_product_loop_start();

                global $offer;
                global $post;
                foreach ($offers as $offer) {
                    $post = get_post($offer['product']['ID']);
                    wc_get_template_part('content', 'offer');

                    $offer = [];
                }

                woocommerce_product_loop_end();
            }
        }

        // üres a lista?
        if ($entityNotFound) {
            {?>
                <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearch'); ?></p>
            <?php }
        }

        wp_reset_postdata();
    }

    /**
     * @param $attributes
     */
    public function networkOffersCloseToFulfillmentByQty($attributes)
    {
        extract(shortcode_atts([
            'per_page' => '12',
            'columns'  => '5',
            'orderby'  => 'current_quantity',
            'order'    => 'desc',
        ], $attributes));

        $entityNotFound = true;

        $followedVendors = (new VendorRepository())->getFollowedUserIds(get_current_user_id());

        if (count($followedVendors)) {
            $offerRepository = new OfferRepository();

            $offerSearchParams = [
                'recent_offers' => true,
                'user_id' => array_column($followedVendors, 'vendor_id'),
                'order'         => ['columnName' => $orderby, 'direction' => $order],
            ];

            $offers = $offerRepository->paginate($offerSearchParams, $per_page, 1);

            if (count($offers)) {
                $entityNotFound = false;
                $offers = (new Offer())->prepareOffers($offers, true, true, true, true);

                woocommerce_product_loop_start();

                global $offer;
                global $post;
                foreach ($offers as $offer) {
                    $post = get_post($offer['product']['ID']);
                    wc_get_template_part('content', 'offer');

                    $offer = [];
                }

                woocommerce_product_loop_end();
            }
        }

        // üres a lista?
        if ($entityNotFound) {
            {?>
                <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearch'); ?></p>
            <?php }
        }

        wp_reset_postdata();
    }

    /**
     * @param $attributes
     */
    public function networkOffersCloseToFulfillmentByTime($attributes)
    {
        extract(shortcode_atts([
            'per_page' => '12',
            'columns'  => '5',
            'orderby'  => 'offer_end_date',
            'order'    => 'desc',
        ], $attributes));

        $entityNotFound = true;
        $followedVendors = (new VendorRepository())->getFollowedUserIds(get_current_user_id());

        if (count($followedVendors)) {
            $offerRepository = new OfferRepository();

            $offerSearchParams = [
                'recent_offers' => true,
                'user_id' => array_column($followedVendors, 'vendor_id'),
                'order'         => ['columnName' => $orderby, 'direction' => $order],
            ];

            $offers = $offerRepository->paginate($offerSearchParams, $per_page, 1);

            if (count($offers)) {
                $entityNotFound = false;
                $offers = (new Offer())->prepareOffers($offers, true, true, true, true);

                woocommerce_product_loop_start();

                global $offer;
                global $post;
                foreach ($offers as $offer) {
                    $post = get_post($offer['product']['ID']);
                    wc_get_template_part('content', 'offer');

                    $offer = [];
                }

                woocommerce_product_loop_end();
            }
        }

        // üres a lista?
        if ($entityNotFound) {
         {?>
             <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearch'); ?></p>
         <?php }

        }

        wp_reset_postdata();
    }

    /**
     * Főoldali shortcode:
     * @see /wp-content/plugins/dokan-lite/includes/Shortcodes/Stores.php
     *
     * @param $attributes
     */
    public function networkRecommendedVendorsByIndustry($attributes)
    {
        extract(shortcode_atts([
            'columns'  => '5',
            'orderby'  => 'ID',
            'order'    => 'desc',
            'per_page' => 10,
            'search'   => 'yes',
            'per_row'  => 3,
            'featured' => 'no',
        ], $attributes));

        $currentVendorProfileSettings = get_user_meta(get_current_user_id(), 'dokan_profile_settings');

        $settings = reset($currentVendorProfileSettings);

        $recommendedVendors = (new UserRepository())->paginate([
            'except_ids' => get_current_user_id(),
            'industry'   => $settings['vendor_industry'],
            'order'         => ['columnName' => $orderby, 'direction' => $order],
        ], $per_page, 1, OBJECT);

        /**
         * Filter for store listing args
         *
         * @since 2.4.9
         */
        $template_args = apply_filters(
            'dokan_store_list_args', array(
                'sellers'    => ['users' => $recommendedVendors],
                'image_size' => 'full',
            )
        );

        ob_start();
        dokan_get_template_part( 'store-lists', false, $template_args );
        $content = ob_get_clean();

        return apply_filters( 'dokan_seller_listing', $content, $attributes );

    }

    /**
     * @param $attributes
     */
    public function networkRecommendedProducts($attributes)
    {
        extract(shortcode_atts([
            'per_page' => '12',
            'columns'  => '5',
            'orderby'  => 'ID',
            'order'    => 'desc',
        ], $attributes));

        $entityNotFound = true;
        $currentVendorProfileSettings = get_user_meta(get_current_user_id(), 'dokan_profile_settings');

        if (count($currentVendorProfileSettings)) {
            $settings = reset($currentVendorProfileSettings);

            $recommendedVendors = (new UserRepository())->paginate([
                'except_ids' => get_current_user_id(),
                'industry' => $settings['vendor_industry'],
                'order' => ['columnName' => $orderby, 'direction' => $order],
            ], $per_page, 1);

            if (count($recommendedVendors)) {
                $productRepository = new ProductRepository();

                $searchParams = [
                    'recent_offers' => true,
                    'user_id' => array_column($recommendedVendors, 'ID'),
                    'order'         => ['columnName' => $orderby, 'direction' => $order],
                ];

                $products = $productRepository->paginate($searchParams, $per_page, 1);

                if (count($products)) {
                    $entityNotFound = false;
                    // Remco itt van midnen product (post) id, ha esetleg le lehet az összeset kérni, valami optimalizált formában
//                $productIds = array_column($products, 'ID');
//                print '<pre>';var_dump($productIds);die;

                    // Remco egyesével így tudsz végig menni ...
//                    print '<pre>';
//                    foreach ($products as $product) {
//                        var_dump($product);
//                        echo '<br>';
//                    }

//                woocommerce_product_loop_start();

//                woocommerce_product_loop_end();
                    //TODO kristof itt valahogy a fenti adatokat kene berakni....valahogy az csak egy pelda amit jelenleg van.
                    ob_start(); ?>
                    <ul class="products">
                        <?php
                        $best_selling_query = dokan_get_top_rated_products();
                        while ( $best_selling_query->have_posts() ) {
                            $best_selling_query->the_post();

                            wc_get_template_part( 'content', 'product' );
                        }
                        ?>
                    </ul>
                    <?php
                    wp_reset_postdata();
                    return ob_get_clean();
                }
            }
        }

        // üres a lista?
        if ($entityNotFound) {
            wc_get_template('loop/no-products-found.php');
        }

        wp_reset_postdata();
    }

    /**
     * @param $attributes
     */
    public function networkRecommendedOffers($attributes)
    {
        extract(shortcode_atts([
            'per_page' => '12',
            'columns'  => '5',
            'orderby'  => 'id',
            'order'    => 'desc',
        ], $attributes));

        $entityNotFound = true;
        $currentVendorProfileSettings = get_user_meta(get_current_user_id(), 'dokan_profile_settings');

        if (count($currentVendorProfileSettings)) {
            $settings = reset($currentVendorProfileSettings);

            $recommendedVendors = (new UserRepository())->paginate([
                'except_ids' => get_current_user_id(),
                'industry'   => $settings['vendor_industry']
            ], $per_page, 1);

            if (count($recommendedVendors)) {

                $offerRepository = new OfferRepository();

                $offerSearchParams = [
                    'user_id' => array_column($recommendedVendors, 'ID'),
                    'order'         => ['columnName' => $orderby, 'direction' => $order],
                ];

                $offers = $offerRepository->paginate($offerSearchParams, $per_page, 1);

                if (count($offers)) {
                    $entityNotFound = false;
                    $offers = (new Offer())->prepareOffers($offers, true, true, true, true);

                    woocommerce_product_loop_start();

                    global $offer;
                    global $post;
                    foreach ($offers as $offer) {
                        $post = get_post($offer['product']['ID']);
                        wc_get_template_part('content', 'offer');

                        $offer = [];
                    }

                    woocommerce_product_loop_end();
                }
            }
        }

        // üres a lista?
        if ($entityNotFound) {
            {?>
                <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearch'); ?></p>
            <?php }
        }

        wp_reset_postdata();
    }
}