<?php

namespace SynerBay\Module;

use SynerBay\Traits\Module as ModuleTrait;
use SynerBay\Traits\WPActionLoader;

class Offer
{
    use ModuleTrait, WPActionLoader;

    private $dataMap = [
        'product_id'             => '%d',
        'user_id'                => '%d',
        'delivery_date'          => '%s',
        'offer_start_date'       => '%s',
        'offer_end_date'         => '%s',
        'minimum_order_quantity' => '%d',
        'order_quantity_step'    => '%d',
        'max_total_offer_qty'    => '%d',
        'weight_unit'            => '%d',
        'weight_unit_sign'       => '%s',
        'material'               => '%s',
        'transport_parity'       => '%s',
        'shipping_to'               => '%s',
    ];

    public function createOffer(array $data)
    {
        global $wpdb;
        $lastInsertedID = false;
        $table = $wpdb->prefix . 'offers';
        $data['user_id'] = get_current_user_id();

        try {
            $wpdb->insert($table, $this->cleanData($data), $this->getInsertFormat());
            $lastInsertedID = $wpdb->insert_id;
        } catch (Exception $e) {

        }

        return $lastInsertedID;
    }

    public function getOfferCurrentData(int $offerID, bool $withUser = false, bool $withApplies = true): array
    {
        $ret = [];

        if ($offer = $this->getOffer($offerID)) {
            $offer['material'] = explode(',', $offer['material']);

            if ($withUser) {
                $offer['user'] = dokan_get_vendor($offer['user_id']);
            }

            /** @var Product $productModule */
            $productModule = $this->getModule('product');
            $offer['product'] = $productModule->getProductData($offer['product_id']);
            var_dump($offer);die;
//            $ret = [
//                'id' =>
//            ];
        }

        return $ret;
    }

    public function getOffer(int $offerID, $output = ARRAY_A)
    {
        global $wpdb;
        return $wpdb->get_row('select * from '.$wpdb->prefix.'offers where id = ' . $offerID, $output);
    }
}