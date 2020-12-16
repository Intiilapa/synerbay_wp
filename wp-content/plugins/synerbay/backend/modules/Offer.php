<?php

namespace SynerBay\Module;

use SynerBay\Traits\Loader;
use SynerBay\Traits\Module as ModuleTrait;
use Exception;

class Offer
{
    use ModuleTrait, Loader;

//    private $commissionMultiplier = 0.03;
    private $commissionMultiplier = 0;

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

    public function createOffer(array $filteredData)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'offers';
        $filteredData['user_id'] = get_current_user_id();

        try {
            $wpdb->insert($table, $this->cleanData($filteredData), $this->getInsertFormat());
            $lastInsertedID = $wpdb->insert_id;
        } catch (Exception $e) {
            return false;
        }

        return $lastInsertedID;
    }

    public function updateOffer(int $offerID, array $data)
    {
        global $wpdb;

        if ($offer = $this->getOfferData($offerID)) {
            if ($offer['user_id'] != get_current_user_id()) {
                throw new Exception('Permission denied!');
            }

            if (strtotime($offer['offer_start_date']) <= strtotime(date('Y-m-d H:i:s'))) {
                throw new Exception('It cannot be modified because it has started! ('.$offerID.')');
            }

            if (array_key_exists('price_steps', $data)) {
                $data['price_steps'] = json_encode($data['price_steps']);
            }

            $table = $wpdb->prefix . 'offers';

            try {
                $wpdb->update(
                    $table,
                    $this->cleanUpdateData($data),
                    array( 'id' => $offerID ),
                    $this->getInsertFormat($data),
                    array( '%d' )
                );
            } catch (Exception $e) {

            }

            return $offerID;
        }

        return false;
    }

    public function deleteOffer(int $userID, int $offerID): bool
    {
        global $wpdb;
        if ($offer = $this->getOfferData($offerID)) {
            try {
                if ($offer['user_id'] != $userID) {
                    throw new Exception('Permission denied!');
                }

                if (count($offer['applies'])) {
                    throw new Exception('Active appears in offer!');
                }

                if (strtotime($offer['offer_start_date']) <= strtotime(date('Y-m-d H:i:s'))) {
                    throw new Exception('It cannot be delete because it has started! ('.$offerID.')');
                }

                return $wpdb->delete($wpdb->prefix . 'offers', ['id' => $offerID]);
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }

    public function getOfferData(int $offerID, bool $withUser = false, bool $withApplies = true, bool $applyWithCustomerData = false, bool $withWCProduct = false)
    {
        if ($offer = $this->getOffer($offerID)) {
            $offer['material'] = explode(',', $offer['material']);
            $offer['price_steps'] = json_decode($offer['price_steps'], true);
            // todo Remco ide kellene az url generálás
            $offer['url'] = get_site_url() . '/offers/' . $offerID;

            if ($withUser) {
                $offer['vendor'] = dokan_get_vendor($offer['user_id']);
            }

            /** @var Product $productModule */
            $productModule = $this->getModule('product');
            $offer['product'] = $productModule->getProductData($offer['product_id'], $withWCProduct);

            /** @var OfferApply $offerApplyModule */
            $offerApplyModule = $this->getModule('offerApply');
            $offer['applies'] = $offerApplyModule->getAppliesForOffer($offerID, $applyWithCustomerData);

            $offer['summary'] = $this->getOfferSummaryData($offer);
            // format prices
            $offer['summary']['formatted_actual_product_price'] = wc_price($offer['summary']['actual_product_price']);
            $offer['summary']['formatted_actual_commission_price'] = wc_price($offer['summary']['actual_commission_price']);
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
     * @return array
     */
    private function getOfferSummaryData(array $offerData)
    {
        $actualProductPrice = $offerData['product']['meta']['_price'];
        $priceSteps = $offerData['price_steps'];
        $groupByProductQTYNumber = 0;
        $actualApplicantNumber = 0;
        $actualPriceStepQty = 0;
        $minPriceStep = 0;
        $maxPriceStep = 0;

        // clean steps
        if (count($priceSteps) && count($offerData['applies'])) {
            $actualApplicantNumber = count($offerData['applies']);

            $tmp = [];
            foreach ($priceSteps as $stepData) {
                $tmp[$stepData['qty']] = $stepData['price'];
            }

            if (count($tmp)) {
                // calculate apply qty
                foreach ($offerData['applies'] as $apply) {
                    $groupByProductQTYNumber += $apply['qty'];
                }

                ksort($tmp);
                foreach ($tmp as $qty => $stepPrice) {
                    $actualPriceStepQty = $qty;

                    // mi az ára?
                    if ($groupByProductQTYNumber < $qty) {
                        break;
                    }

                    $actualProductPrice = $stepPrice;
                }

                // get max price step
                $minPriceStep = array_key_first($tmp);
                $maxPriceStep = array_key_last($tmp);
            }

            unset($tmp);
        }

        return [
            'actual_product_price' => $actualProductPrice,
            'min_price_step_qty' => $minPriceStep,
            'max_price_step_qty' => $maxPriceStep,
            'actual_price_step_qty' => $actualPriceStepQty,
            'actual_applicant_product_number' => $groupByProductQTYNumber,
            'actual_commission_price' => $actualApplicantNumber > 0 ? (($groupByProductQTYNumber * $actualProductPrice) * $this->commissionMultiplier) : 0,
            'actual_applicant_number' => $actualApplicantNumber,
        ];
    }

    public function getMyOffersForDashboard()
    {
        global $wpdb;

        $ret = [];

        $results = $wpdb->get_results('select id from '.$wpdb->prefix.'offers where user_id = ' . get_current_user_id() . ' order by id desc', ARRAY_A);

        if (count($results)) {
            foreach ($results as $result) {
                $ret[] = $this->getOfferData($result['id']);
            }
        }

        return $ret;

    }
}