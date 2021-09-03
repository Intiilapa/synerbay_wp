<?php

namespace SynerBay\Module;

use Exception;
use SynerBay\Resource\Offer\DefaultOfferResource;
use SynerBay\Resource\Offer\FullOfferResource;
use SynerBay\Traits\Loader;
use SynerBay\Traits\Module as ModuleTrait;
use SynerBay\Traits\Redirector;
use SynerBay\Traits\Toaster;

class Offer extends AbstractModule
{
    use ModuleTrait, Loader, Toaster, Redirector;

//    private $commissionMultiplier = 0.03;
    private $commissionMultiplier = 0;
    private $fictitiousCommissionMultiplier = 0.03;

    private $dataMap = [
        'product_id'             => '%d',
        'default_price'          => '%s',
        'user_id'                => '%d',
        'delivery_date'          => '%s',
        'offer_start_date'       => '%s',
        'offer_end_date'         => '%s',
        'price_steps'            => '%s',
        'minimum_order_quantity' => '%d',
        'order_quantity_step'    => '%d',
        'max_total_offer_qty'    => '%d',
        'transport_parity'       => '%s',
        'shipping_to'            => '%s',
        'visible'                => '%s',
        'payment_term'           => '%s',
        'currency'               => '%s',
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

        $this->deleteGroupFromCache('offer_resource');

        $this->addSuccessToast('Successful operation');

//        $this->redirectToUpdateOffer($lastInsertedID);
        return $lastInsertedID;
    }

    public function updateOffer(array $filteredData)
    {
        global $wpdb;

        try {
            if ($offer = $this->getOfferData($filteredData['offer_id'])) {
                if ($offer['user_id'] != get_current_user_id()) {
                    throw new Exception('Permission denied!');
                }

                if (strtotime($offer['offer_start_date']) <= strtotime(date('Y-m-d H:i:s'))) {
                    throw new Exception('It cannot be modified because it has started! (' . $offer['id'] . ')');
                }

                unset($filteredData['offer_id']);

                $table = $wpdb->prefix . 'offers';

                $wpdb->update(
                    $table,
                    $this->cleanUpdateData($filteredData),
                    ['id' => $offer['id']],
                    $this->getInsertFormat($filteredData),
                    ['%d']
                );

                $this->deleteGroupFromCache('offer_resource');

                $this->addSuccessToast('Successful operation');

                return true;
            }
        } catch (Exception $e) {
            $this->addErrorToast($e->getMessage());
            $this->addErrorMsg($e->getMessage());
        }

        return false;
    }

    public function getOfferData(
        int $offerID,
        bool $withUser = false,
        bool $withApplies = true,
        bool $applyWithCustomerData = false,
        bool $withWCProduct = false
    ) {
        if ($offer = $this->getOffer($offerID)) {
            $offer = (new FullOfferResource)->toArray($offer);
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

        return $wpdb->get_row('select * from ' . $wpdb->prefix . 'offers where id = ' . $offerID, $output);
    }

    /**
     * @param array $offerData
     * @return array
     */
    public function getOfferSummaryData(array $offerData)
    {
        $currentDate = strtotime(date('Y-m-d H:i:s'));
        $actualProductPrice = $offerData['default_price'];
        $defaultProductPrice = $offerData['default_price'];
        $priceSteps = $offerData['price_steps'];
        $groupByProductQTYNumber = 0;
        $actualApplicantNumber = 0;
        $actualPriceStepQty = 0;
        $minPriceStep = 0;
        $maxPriceStep = 0;
        $myOffer = $offerData['user_id'] == get_current_user_id();
        $active = $currentDate >= strtotime($offerData['offer_start_date']) || $currentDate <= strtotime($offerData['offer_end_date']);
        $currentUserHaveApply = false;
        $currentUserApplyQty = 0;
        $actualCommissionPrice = 0;
        $fictitiousCommissionPrice = 0;

        // get dokan admin commision percantage
        $sellingOptions = get_option( 'dokan_selling', array() );

        if (! empty( $sellingOptions['admin_percentage'] )) {
            $this->commissionMultiplier = (float)$sellingOptions['admin_percentage'];
        }

        $tmp = [];
        foreach ($priceSteps as $stepData) {
            $tmp[$stepData['qty']] = $stepData['price'];
        }

        ksort($tmp);
        $minPriceStep = array_key_first($tmp);
        $minPriceStepPrice = $tmp[$minPriceStep];
        $maxPriceStep = array_key_last($tmp);
        $maxPriceStepPrice = $tmp[$maxPriceStep];

        // clean steps
        if (count($tmp) && count($offerData['applies'])) {
            // calculate apply qty
            foreach ($offerData['applies'] as $apply) {
                if ($apply['user_id'] == get_current_user_id()) {
                    $currentUserHaveApply = true;
                    $currentUserApplyQty = (int)$apply['qty'];
                }

                if ($apply['status'] == \SynerBay\Model\OfferApply::STATUS_ACTIVE) {
                    $actualApplicantNumber++;
                    $groupByProductQTYNumber += (int)$apply['qty'];
                }
            }

            foreach ($tmp as $qty => $stepPrice) {
                // mi az ára?
                if ($groupByProductQTYNumber < $qty) {
                    break;
                }

                $actualPriceStepQty = $qty;
                $actualProductPrice = $stepPrice;
            }
        }

        if ($actualProductPrice > 0 && $actualApplicantNumber > 0) {
            $actualCommissionPrice = (($groupByProductQTYNumber * $actualProductPrice) * $this->commissionMultiplier);
            $fictitiousCommissionPrice = (($groupByProductQTYNumber * $actualProductPrice) * $this->fictitiousCommissionMultiplier);
        }

        // egyéb adatok
        $nextPriceStepQty = $minPriceStep;
        $nextPriceStepPrice = $minPriceStepPrice;

        foreach ($tmp as $priceStepQty => $priceStepPrice) {
            $nextPriceStepQty = $priceStepQty;
            $nextPriceStepPrice = $priceStepPrice;

            if ($priceStepQty > $actualPriceStepQty) {
                break;
            }
        }

        // teljesített az offer qty-ben
        /**
         * @info a summary kulcs offer_qty_successful adjuk meg (bool)
         */

        // hot offer (az utolsó előtt lépcsőben van a teljesítése)
        $hotOffer = (int)$actualPriceStepQty == (int)$maxPriceStep;

        // hamarosan lejáró offer (a lejáratig kevesebb mint 1 hét van)
        $lastMinuteOfferStartTimeFlag = strtotime( $offerData['offer_end_date'] . " -7 days");
        $lastMinuteOffer = strtotime('now') >= $lastMinuteOfferStartTimeFlag;

        // jelenlegi kedvezmény %-ban / árban
        $currentDiscountPercentage = round(100 - (($actualProductPrice / $offerData['default_price']) * 100), 2);
        $currentDiscountPrice = $offerData['default_price'] - $actualProductPrice;

        // maximális step árához viszonított ár és kedvezmény az alapárból
        $maxDiscountPercentage = round(100 - (($maxPriceStepPrice / $offerData['default_price']) * 100), 2);
        $maxDiscountPrice = $offerData['default_price'] - $maxPriceStepPrice;

        // mennyi kell a következő kedvezményig
        $nextPriceStepRequiredQty = $nextPriceStepQty - $groupByProductQTYNumber;

        // a következő lépcsőnél mennyi a kedvezmény az aktuálishoz mérten
        $nextPriceStepDiscountFromCurrentPercentage = round(100 - (($nextPriceStepPrice / $actualProductPrice) * 100), 2);
        $nextPriceStepDiscountFromCurrentPrice = $actualProductPrice - $nextPriceStepPrice;

        // a következő lépcsőnél mennyi a kedvezmény az alaphoz képest
        $nextPriceStepDiscountFromDefaultPercentage = round(100 - (($nextPriceStepPrice / $offerData['default_price']) * 100), 2);
        $nextPriceStepDiscountFromDefaultPrice = $offerData['default_price'] - $nextPriceStepPrice;

        unset($tmp);

        return [
            'is_active'                             => $active,
            'my_offer'                              => $myOffer,
            'current_user_have_apply'               => $currentUserHaveApply,
            'current_user_apply_qty'                => $currentUserApplyQty,
            'actual_product_price'                  => wc_price($actualProductPrice, ['currency' => strtoupper($offerData['currency'])]),
            'default_product_price'                 => wc_price($defaultProductPrice, ['currency' => strtoupper($offerData['currency'])]),
            'min_price_step_qty'                    => $minPriceStep,
            'min_price_step_price'                  => wc_price($minPriceStepPrice, ['currency' => strtoupper($offerData['currency'])]),
            'max_price_step_qty'                    => $maxPriceStep,
            'max_price_step_price'                  => wc_price($maxPriceStepPrice, ['currency' => strtoupper($offerData['currency'])]),
            'actual_price_step_qty'                 => $actualPriceStepQty,
            'actual_applicant_product_number'       => $groupByProductQTYNumber,
            'actual_commission_price'               => wc_price($actualCommissionPrice, ['currency' => strtoupper($offerData['currency'])]),
            'actual_applicant_number'               => $actualApplicantNumber,
            'formatted_actual_product_price'        => wc_price($actualProductPrice, ['currency' => strtoupper($offerData['currency'])]),
            'formatted_actual_default_product_price'=> wc_price($defaultProductPrice, ['currency' => strtoupper($offerData['currency'])]),
            'formatted_actual_commission_price'     => wc_price($actualCommissionPrice, ['currency' => strtoupper($offerData['currency'])]),
            'fictitious_commission_price'           => wc_price($fictitiousCommissionPrice, ['currency' => strtoupper($offerData['currency'])]),
            'formatted_fictitious_commission_price' => wc_price($fictitiousCommissionPrice, ['currency' => strtoupper($offerData['currency'])]),

            // metadata
            'offer_qty_successful'                              => (int)$groupByProductQTYNumber >= (int)$maxPriceStep,
            'last_minute_offer'                                 => $lastMinuteOffer,
            'current_discount_percentage_from_default_price'    => $currentDiscountPercentage,
            'current_discount_price_from_default_price'         => wc_price($currentDiscountPrice, ['currency' => strtoupper($offerData['currency'])]),
            'max_discount_percentage_from_default_price'        => $maxDiscountPercentage,
            'max_discount_price_from_default_price'             => wc_price($maxDiscountPrice, ['currency' => strtoupper($offerData['currency'])]),
            'next_price_step_required_qty'                      => $nextPriceStepRequiredQty,
            'next_price_step_discount_percentage_from_current'  => $nextPriceStepDiscountFromCurrentPercentage,
            'next_price_step_discount_price_from_current'       => wc_price($nextPriceStepDiscountFromCurrentPrice, ['currency' => strtoupper($offerData['currency'])]),
            'next_price_step_discount_percentage_from_default'  => $nextPriceStepDiscountFromDefaultPercentage,
            'next_price_step_discount_price_from_default'       => wc_price($nextPriceStepDiscountFromDefaultPrice, ['currency' => strtoupper($offerData['currency'])]),
            'hot_offer'                                         => $hotOffer,
        ];
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
                    throw new Exception('It cannot be delete because it has active applicants!');
                }

                if (strtotime($offer['offer_start_date']) <= strtotime(date('Y-m-d H:i:s'))) {
                    throw new Exception('It cannot be delete because it has started! (' . $offerID . ')');
                }

                $deleted = $wpdb->delete($wpdb->prefix . 'offers', ['id' => $offerID]);

                if ($deleted) {
                    $this->deleteGroupFromCache('offer_resource');
                    $this->addSuccessToast('Successful operation');
                }

                return $deleted;
            } catch (Exception $e) {
                $this->addErrorToast($e->getMessage());
                $this->addErrorMsg($e->getMessage());

                return false;
            }
        }

        return false;
    }

    public function getMyOffersForDashboard()
    {
        global $wpdb;

        $ret = [];

        $results = $wpdb->get_results('select * from ' . $wpdb->prefix . 'offers where user_id = ' . get_current_user_id() . ' order by id desc',
            ARRAY_A);

        if (count($results)) {
            $resource = new DefaultOfferResource();
            foreach ($results as $result) {
                $ret[] = $resource->toArray($result);
            }
        }

        return $ret;

    }

    public function prepareOffers(
        $offerIds = [],
        bool $withUser = false,
        bool $withApplies = true,
        bool $applyWithCustomerData = false,
        bool $withWCProduct = false
    ) {
        $data = [];

        if (count($offerIds)) {

            foreach ($offerIds as $queryResult) {
                $data[] = $this->getOfferData($queryResult['id'], $withUser, $withApplies, $applyWithCustomerData,
                    $withWCProduct);
            }
        }

        return $data;
    }
}