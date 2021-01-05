<?php

namespace SynerBay\Module;

use SynerBay\Helper\RouteHelper;
use SynerBay\Helper\StringHelper;
use SynerBay\Helper\SynerBayDataHelper;
use SynerBay\Traits\Loader;
use SynerBay\Traits\Module as ModuleTrait;
use Exception;
use SynerBay\Traits\Redirector;
use SynerBay\Traits\Toaster;

class Offer extends AbstractModule
{
    use ModuleTrait, Loader, Toaster, Redirector;

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
            $offer['price_steps'] = StringHelper::isJson($offer['price_steps']) ? json_decode($offer['price_steps'],
                true) : [];
            $offer['shipping_to'] = StringHelper::isJson($offer['shipping_to']) ? json_decode($offer['shipping_to'],
                true) : [$offer['shipping_to']];
            $offer['shipping_to_labels'] = implode(', ',
                SynerBayDataHelper::setupDeliveryDestinationsForOfferData($offer['shipping_to']));
            $offer['url'] = RouteHelper::generateRoute('offer_sub_page', ['id' => $offer['id']]);
            $offer['transport_parity'] = strtoupper($offer['transport_parity']);

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
    private function getOfferSummaryData(array $offerData)
    {
        $currentDate = strtotime(date('Y-m-d H:i:s'));
        $actualProductPrice = isset($offerData['product']['meta']['_regular_price']) ? $offerData['product']['meta']['_regular_price'] : '-';
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

                    if ($apply['user_id'] == get_current_user_id()) {
                        $currentUserHaveApply = true;
                        $currentUserApplyQty = $apply['qty'];
                    }
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

        $actualCommissionPrice = $actualApplicantNumber > 0 ? (($groupByProductQTYNumber * $actualProductPrice) * $this->commissionMultiplier) : 0;

        return [
            'is_active'                         => $active,
            'my_offer'                          => $myOffer,
            'current_user_have_apply'           => $currentUserHaveApply,
            'current_user_apply_qty'            => $currentUserApplyQty,
            'actual_product_price'              => $actualProductPrice,
            'min_price_step_qty'                => $minPriceStep,
            'max_price_step_qty'                => $maxPriceStep,
            'actual_price_step_qty'             => $actualPriceStepQty,
            'actual_applicant_product_number'   => $groupByProductQTYNumber,
            'actual_commission_price'           => $actualCommissionPrice,
            'actual_applicant_number'           => $actualApplicantNumber,
            'formatted_actual_product_price'    => wc_price($actualProductPrice),
            'formatted_actual_commission_price' => wc_price($actualCommissionPrice),
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

        $results = $wpdb->get_results('select id from ' . $wpdb->prefix . 'offers where user_id = ' . get_current_user_id() . ' order by id desc',
            ARRAY_A);

        if (count($results)) {
            foreach ($results as $result) {
                $ret[] = $this->getOfferData($result['id']);
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