<?php

namespace SynerBay\Module;

use WC_Product;

class Product
{
    public function getProductData(int $productID): array
    {
        $productData = [];

        if ($product = $this->getProduct($productID)) {
            $productData = [
                'id'    => $product->get_id(),
                'name'  => $product->get_formatted_name(),
                'price' => $product->get_price(),
                'url'   => get_permalink($product->get_id()),
            ];
        }

        return $productData;
    }

    public function search(array $searchParams = [])
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

        return $wpdb->query('select ID from ' . $table . ' where ' . implode('and', $where));
    }

    /**
     * @param int $productID
     * @return false|WC_Product|null
     */
    private function getProduct(int $productID)
    {
        if ($product = wc_get_product($productID)) {
            return $product;
        }

        return false;
    }
}