<?php

namespace SynerBay\Module;

use WC_Product;
use WC_Meta_Data;

class Product
{
    public function getProductData(int $productID, $withWCProduct = true, $withMetaData = true): array
    {
        $productData = [];

        if ($product = $this->getProduct($productID)) {
            $productData = $product;

            if ($withMetaData) {
                $productData['meta'] = $this->getProductMetaData($productID);
            }

            if ($withWCProduct) {
                $productData['wc_product'] = wc_get_product($productID);
            }
        }

        return $productData;
    }

    public function search(array $searchParams = [], $output = ARRAY_A)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'posts';

        $where = [
            ['post_type = "product"']
        ];

        if (isset($searchParams['user_id'])) {
            if (is_array($searchParams['user_id'])) {
                $where[] = ['post_author in (' . implode(', ', $searchParams['user_id']) . ')'];
            } else {
                $where[] = ['post_author = ' . $searchParams['user_id']];
            }
        }

        return $wpdb->get_results('select ID from ' . $table . ' where ' . implode('and', $where), $output);
    }

    /**
     * @param int    $productID
     * @param string $output
     * @return array|false|object|void|WC_Product|null
     */
    private function getProduct(int $productID, $output = ARRAY_A)
    {
        global $wpdb;
        return $wpdb->get_row('select * from '.$wpdb->prefix.'posts where post_type = "product" and ID = ' . $productID, $output);
    }

    /**
     * @param int    $productID
     * @param string $output
     * @return array|object|null
     */
    private function getProductMetaData(int $productID, $output = ARRAY_A)
    {
        global $wpdb;
        $ret = [];

        if ($results = $wpdb->get_results('select meta_key, meta_value from '.$wpdb->prefix.'postmeta where post_id = ' . $productID, $output)) {
            foreach ($results as $result) {
                $ret[$result['meta_key']] = $result['meta_value'];
            }
        }

        return $ret;
    }
}