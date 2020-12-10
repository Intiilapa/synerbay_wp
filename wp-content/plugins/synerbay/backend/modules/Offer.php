<?php

namespace SynerBay\Module;

use SynerBay\Traits\Loader;
use SynerBay\Traits\Module as ModuleTrait;

class Offer
{
    use ModuleTrait, Loader;

    private $dataMap = [
        'product_id'             => '%d',
        'user_id'                => '%d',
        'delivery_date'          => '%s',
        'offer_start_date'       => '%s',
        'offer_end_date'         => '%s',
        'price_steps'            => '%s',
        'minimum_order_quantity' => '%d',
        'order_quantity_step'    => '%d',
        'max_total_offer_qty'    => '%d',
        'weight_unit'            => '%d',
        'weight_unit_sign'       => '%s',
        'material'               => '%s',
        'transport_parity'       => '%s',
        'shipping_to'            => '%s',
    ];

    public function createOffer(array $data)
    {
        global $wpdb;
        $lastInsertedID = false;
        $table = $wpdb->prefix . 'offers';
        $data['user_id'] = get_current_user_id();
        $data['price_steps'] = json_encode($data['price_steps']);

        try {
            $wpdb->insert($table, $this->cleanData($data), $this->getInsertFormat());
            $lastInsertedID = $wpdb->insert_id;
        } catch (Exception $e) {

        }

        return $lastInsertedID;
    }

    public function getOfferData(int $offerID, bool $withUser = false, bool $withApplies = true): array
    {
        if ($offer = $this->getOffer($offerID)) {
            $offer['material'] = explode(',', $offer['material']);
            $offer['price_steps'] = json_decode($offer['price_steps'], true);
            // todo Remco ide kellene az url generálás
            $offer['url'] = get_permalink($offerID);

            if ($withUser) {
                $offer['user'] = dokan_get_vendor($offer['user_id']);
            }

            /** @var Product $productModule */
            $productModule = $this->getModule('product');
            $offer['product'] = $productModule->getProductData($offer['product_id']);

            /** @var OfferApply $offerApplyModule */
            $offerApplyModule = $this->getModule('offerApply');
            $offer['applies'] = $offerApplyModule->getAppliesForOffer($offerID);

            // calculate current price
            $offer['current_price'] = $this->calculateCurrentPrice($offer);
        }

        return $offer;
    }

    /**
     * @param int    $offerID
     * @param string $output
     * @return array|object|void|null
     */
    public function getOffer(int $offerID, $output = ARRAY_A)
    {
        global $wpdb;
        return $wpdb->get_row('select * from '.$wpdb->prefix.'offers where id = ' . $offerID, $output);
    }

    /**
     * @param array $offerData
     * @return mixed
     */
    public function calculateCurrentPrice(array $offerData)
    {
        $price = $offerData['product']['meta']['_price'];
        $priceSteps = $offerData['price_steps'];

        // clean steps
        if (count($priceSteps) && count($offerData['applies'])) {
            $tmp = [];
            foreach ($priceSteps as $stepData) {
                $tmp[$stepData['qty']] = $stepData['price'];
            }

            if (count($tmp)) {
                // calculate apply qty
                $appliesSum = 0;

                foreach ($offerData['applies'] as $apply) {
                    $appliesSum += $apply['qty'];
                }

                ksort($tmp);
                foreach ($tmp as $qty => $stepPrice) {
                    // mi az ára?
                    if ($appliesSum < $qty) {
                        break;
                    }

                    $price = $stepPrice;
                }
            }

            unset($tmp);
        }

        return $price;
    }
}