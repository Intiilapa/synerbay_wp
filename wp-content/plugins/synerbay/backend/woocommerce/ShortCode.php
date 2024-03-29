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
        add_action( 'after_setup_theme', [$this, 'fix_product_title_link'], 110 );

        //Homepage Shortcodes - Marketplace
        add_shortcode('home_page_vendors', [$this, 'homePageVendors']);
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
        //Grid - List view
        add_filter('body_class', array($this, 'body_classes'));
    }

    public function fix_product_title_link()
    {
        global $martfury_woocommerce;
        remove_action('woocommerce_shop_loop_item_title', [$martfury_woocommerce, 'products_title'], 10);
        add_action('woocommerce_shop_loop_item_title', function() {
            global $offer;
            if ($offer) {
                $url = $offer['url'];
            } else {
                $url = esc_url( get_the_permalink() );
            }

            printf( '<h2><a href="%s">%s</a></h2>', $url, get_the_title() );

        }, 10);
    }

    /**
     * @param $attributes
     * @return mixed|void
     */
    public function homePageVendors($attributes)
    {
        extract(shortcode_atts([
            'columns' => '5',
            'orderby' => 'ID',
            'order' => 'desc',
            'per_page' => 10,
            'search' => 'yes',
            'per_row' => 3,
            'featured' => 'no',
        ], $attributes));

        $recommendedVendors = (new UserRepository())->paginate([
            'except_ids' => get_current_user_id(),
            'only_verificated' => true,
            'order' => ['columnName' => $orderby, 'direction' => $order],
        ], $per_page, 1, OBJECT);


        $sellers = ['users' => $recommendedVendors];
        $image_size = 'full';

        ob_start();

//        dokan_get_template_part( 'store-lists', false, $template_args );

        do_action('dokan_before_seller_listing_loop', $sellers);

        $template_args = array(
            'sellers' => $sellers,
            'limit' => $limit,
            'offset' => $offset,
            'paged' => $paged,
            'search_query' => $search_query,
            'pagination_base' => $pagination_base,
            'per_row' => $per_row,
            'search_enabled' => $search,
            'image_size' => $image_size,
        );

        dokan_get_template_part('store-lists-loop', false, $template_args);

        /**
         * Action hook after finishing seller listing loop
         *
         * @since 2.8.6
         *
         * @var array $sellers
         */
        do_action('dokan_after_seller_listing_loop', $sellers);
        $content = ob_get_clean();

        return apply_filters('dokan_seller_listing', $content, $attributes);
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
            'columns' => '5',
            'orderby' => 'id',
            'order' => 'desc',
        ]));

        $offerSearch = new OfferSearch([
            'recent_offers' => true,
            'order' => ['columnName' => $orderby, 'direction' => $order],
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
//                print '<pre>';var_dump($offer);die;
                $post = get_post($offer['product']['ID']);
                //var_dump($post);
                wc_get_template_part('content', 'offer');

                $offer = [];
            }

            woocommerce_product_loop_end();
        } else {
            {
                ?>
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
            'columns' => '5',
            'orderby' => 'offer_end_date',
            'order' => 'asc',
        ]));

        $offerSearch = new OfferSearch([
            'recent_offers' => true,
            'order' => ['columnName' => $orderby, 'direction' => $order],
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
            {
                ?>
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
            'columns' => '5',
            'orderby' => 'current_quantity',
            'order' => 'desc',
        ]));

        $offerSearch = new OfferSearch([
            'recent_offers' => true,
            'order' => ['columnName' => $orderby, 'direction' => $order],
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
            {
                ?>
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
            'columns' => '5',
            'orderby' => 'ID',
            'order' => 'desc',
        ], $attributes));

        $entityNotFound = true;
        $followedVendors = (new VendorRepository())->getFollowedUserIds(get_current_user_id());

        if (count($followedVendors)) {
            $productRepository = new ProductRepository();

            $searchParams = [
                'user_id' => array_column($followedVendors, 'vendor_id'),
                'order' => ['columnName' => $orderby, 'direction' => $order],
            ];

            $products = $productRepository->paginate($searchParams, $per_page, 1);

            if (count($products)) {
                $entityNotFound = false;
                ob_start(); ?>
                <ul class="products">
                    <?php
                    global $wp_query;
                    global $post;

                    foreach ($products as $nwProduct) {
                        $post = get_post($nwProduct['ID']);
                        $wp_query->setup_postdata($post);
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
            'columns' => '5',
            'orderby' => 'id',
            'order' => 'desc',
        ], $attributes));

        $entityNotFound = true;

        $followedVendors = (new VendorRepository())->getFollowedUserIds(get_current_user_id());

        if (count($followedVendors)) {
            $offerRepository = new OfferRepository();

            $offerSearchParams = [
                'user_id' => array_column($followedVendors, 'vendor_id'),
                'recent_offers' => true,
                'order' => ['columnName' => $orderby, 'direction' => $order],
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
                    wc_get_template_part('content', 'offerList');

                    $offer = [];
                }

                woocommerce_product_loop_end();
            }
        }

        // üres a lista?
        if ($entityNotFound) {
            {
                ?>
                <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearchNetwork'); ?></p>
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
            'columns' => '5',
            'orderby' => 'current_quantity',
            'order' => 'desc',
        ], $attributes));

        $entityNotFound = true;

        $followedVendors = (new VendorRepository())->getFollowedUserIds(get_current_user_id());

        if (count($followedVendors)) {
            $offerRepository = new OfferRepository();

            $offerSearchParams = [
                'recent_offers' => true,
                'user_id' => array_column($followedVendors, 'vendor_id'),
                'order' => ['columnName' => $orderby, 'direction' => $order],
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
                    wc_get_template_part('content', 'offerList');

                    $offer = [];
                }

                woocommerce_product_loop_end();
            }
        }

        // üres a lista?
        if ($entityNotFound) {
            {
                ?>
                <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearchNetwork'); ?></p>
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
            'columns' => '5',
            'orderby' => 'offer_end_date',
            'order' => 'asc',
        ], $attributes));

        $entityNotFound = true;
        $followedVendors = (new VendorRepository())->getFollowedUserIds(get_current_user_id());

        if (count($followedVendors)) {
            $offerRepository = new OfferRepository();

            $offerSearchParams = [
                'recent_offers' => true,
                'user_id' => array_column($followedVendors, 'vendor_id'),
                'order' => ['columnName' => $orderby, 'direction' => $order],
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
                    wc_get_template_part('content', 'offerList');

                    $offer = [];
                }

                woocommerce_product_loop_end();
            }
        }

        // üres a lista?
        if ($entityNotFound) {
            {
                ?>
                <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearchNetwork'); ?></p>
            <?php }

        }

        wp_reset_postdata();
    }

    /*
     * Főoldali shortcode:
     * @see /wp-content/plugins/dokan-lite/includes/Shortcodes/Stores.php
     *
     * @param $attributes
     */
    public function networkRecommendedVendorsByIndustry($attributes)
    {
        extract(shortcode_atts([
            'columns' => '5',
            'orderby' => 'ID',
            'order' => 'desc',
            'per_page' => 10,
            'search' => 'yes',
            'per_row' => 3,
            'featured' => 'no',
        ], $attributes));

        $currentVendorProfileSettings = get_user_meta(get_current_user_id(), 'dokan_profile_settings');

        $settings = reset($currentVendorProfileSettings);

        $recommendedVendors = (new UserRepository())->paginate([
            'except_ids' => get_current_user_id(),
            'industry' => $settings['vendor_industry'],
            'only_verificated' => true,
            'order' => ['columnName' => $orderby, 'direction' => $order],
        ], $per_page, 1, OBJECT);

        $sellers = ['users' => $recommendedVendors];
        $image_size = 'full';

        ob_start();

//        dokan_get_template_part( 'store-lists', false, $template_args );

        do_action('dokan_before_seller_listing_loop', $sellers);

        $template_args = array(
            'sellers' => $sellers,
            'limit' => $limit,
            'offset' => $offset,
            'paged' => $paged,
            'search_query' => $search_query,
            'pagination_base' => $pagination_base,
            'per_row' => $per_row,
            'search_enabled' => $search,
            'image_size' => $image_size,
        );

        dokan_get_template_part('store-lists-loop', false, $template_args);

        /**
         * Action hook after finishing seller listing loop
         *
         * @since 2.8.6
         *
         * @var array $sellers
         */
        do_action('dokan_after_seller_listing_loop', $sellers);
        $content = ob_get_clean();

        return apply_filters('dokan_seller_listing', $content, $attributes);

    }

    /**
     * @param $attributes
     * @return false|string
     */
    public function networkRecommendedProducts($attributes)
    {
        extract(shortcode_atts([
            'per_page' => '12',
            'columns' => '5',
            'orderby' => 'ID',
            'order' => 'desc',
        ], $attributes));

        $entityNotFound = true;
        $currentVendorProfileSettings = get_user_meta(get_current_user_id(), 'dokan_profile_settings');

        if (count($currentVendorProfileSettings)) {
            $settings = reset($currentVendorProfileSettings);

            $productRepository = new ProductRepository();

            $searchParams = [
                'category_name' => $settings['vendor_industry'],
                'except_user_id' => get_current_user_id(),
                'order' => ['columnName' => $orderby, 'direction' => $order],
            ];

            $products = $productRepository->paginate($searchParams, $per_page, 1);

            if (count($products)) {
                $entityNotFound = false;
                ob_start(); ?>
                <ul class="products">
                    <?php
                    global $wp_query;
                    global $post;

                    foreach ($products as $nwProduct) {
                        $post = get_post($nwProduct['ID']);
                        $wp_query->setup_postdata($post);
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
    public function networkRecommendedOffers($attributes)
    {
        extract(shortcode_atts([
            'per_page' => '12',
            'columns' => '5',
            'orderby' => 'id',
            'order' => 'desc',
        ], $attributes));

        $entityNotFound = true;
        $currentVendorProfileSettings = get_user_meta(get_current_user_id(), 'dokan_profile_settings');

        if (count($currentVendorProfileSettings)) {
            $settings = reset($currentVendorProfileSettings);

            $productRepository = new ProductRepository();

            $searchParams = [
                'category_name' => $settings['vendor_industry'],
                'except_user_id' => get_current_user_id(),
                'order' => ['columnName' => $orderby, 'direction' => $order],
            ];

            $recommendedProducts = $productRepository->search($searchParams);

            if (count($recommendedProducts)) {

                $offerRepository = new OfferRepository();

                $offerSearchParams = [
                    'product_id' => array_column($recommendedProducts, 'ID'),
                    'order' => ['columnName' => $orderby, 'direction' => $order],
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
                        wc_get_template_part('content', 'offerList');

                        $offer = [];
                    }

                    woocommerce_product_loop_end();
                }
            }
        }

        // üres a lista?
        if ($entityNotFound) {
            {
                ?>
                <p class="woocommerce-info"><?php do_action('synerbay_synerBayInviteButtonSearchNetwork'); ?></p>
            <?php }
        }

        wp_reset_postdata();
    }

    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     *
     * @return array
     * @since 1.0
     *
     */
    function body_classes($classes)
    {
        if (is_page('Network')) {
            $shop_view = 'list';
            $classes[] = 'shop-view-' . $shop_view;
            $classes[] = 'woocommerce' . $shop_view;
        }

        return $classes;
    }

}