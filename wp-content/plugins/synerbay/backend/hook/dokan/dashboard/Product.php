<?php

namespace SynerBay\Hook\Dokan\Dashboard;

use SynerBay\Repository\ProductRepository;

class Product
{
    public function __construct()
    {
        // dokan / dashboard / cataloge / row action buttons hook
        add_filter( 'dokan_product_row_actions', [$this, 'product_row_action'], 11, 2 );
    }

    public function product_row_action( $row_action, $post ) {
        if ( empty( $post->ID ) ) {
            return $row_action;
        }

        // amennyiben a producthoz tartozik offer, akkor nem törölhető a termék
        if (array_key_exists('delete', $row_action) && (new ProductRepository())->hasOffer($post->ID)) {
            unset($row_action['delete']);
        }

        //ha meg nem letezik offer a productrol, akkor legyen create offer gomb a row-actions kozott
        if (array_key_exists('delete', $row_action)) {
            $url = get_site_url() . '/dashboard/new-offer?product-id='.$post->ID;
            $row_action['create-offer'] = array(
                'title' => __( 'Create Offer', 'dokan' ),
                'url'   => $url,
                'class' => 'offer_create',
            );
        }

        return $row_action;
    }
}