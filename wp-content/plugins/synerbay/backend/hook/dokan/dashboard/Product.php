<?php

namespace SynerBay\Hook\Dokan\Dashboard;

use SynerBay\Repository\ProductRepository;

class Product
{
    public function __construct()
    {
        add_filter( 'dokan_product_row_actions', array( $this, 'product_row_action' ), 11, 2 );
    }

    public function product_row_action( $row_action, $post ) {
        if ( empty( $post->ID ) ) {
            return $row_action;
        }

        // amennyiben a producthoz tartozik offer, akkor nem törölhető a termék
        if (array_key_exists('delete', $row_action) && (new ProductRepository())->hasOffer($post->ID)) {
            unset($row_action['delete']);
        }

        return $row_action;
    }
}