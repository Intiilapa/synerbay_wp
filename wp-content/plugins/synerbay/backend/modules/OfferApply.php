<?php
namespace SynerBay\Module;

use Exception;
use SynerBay\Traits\Loader;

class OfferApply
{
    use Loader;
    /** @var Offer $offerModule */
    private Offer $offerModule;

    public function __construct()
    {
        $this->offerModule = $this->getModule('offer');
    }

    public function createAppearOfferForUser(int $userID, int $offerID, int $productQuantity)
    {
        global $wpdb;

        try {
            if ($offer = $this->offerModule->getOfferData($offerID)) {
                $currentDateTime = strtotime(date('Y-m-d H:i:s'));
                if ($currentDateTime < strtotime($offer['offer_start_date'])) {
                    throw new Exception('The offer is not started!');
                }

                if ($currentDateTime > strtotime($offer['offer_end_date'])) {
                    throw new Exception('The offer is finished!');
                }

                $table = $wpdb->prefix . 'offer_applies';
                $data = [
                    'user_id'  => $userID,
                    'offer_id' => $offerID,
                    'qty'      => $productQuantity,
                ];
                $format = ['%d', '%d', '%d'];

                if($wpdb->insert($table, $data, $format)) {
                    // TODO REMCO mail-ek kiküldése

                    //        $offerUsers = $this->offerModule->getOfferUsers($offerID);
                    //
                    //        foreach ($offerUsers as $offerUser) {
                    //            if ($offerUser->ID === $userID) {
                    //                // akkor a usernek megy egy köszönöő mail
                    //            } else {
                    //                $this->sendMail($offerUser);
                    //            }
                    //        }

                    return true;
                }


                return false;
            }

        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    public function deleteAppearOfferForUser(int $userID, int $offerID)
    {
        global $wpdb;

        try {
            if ($offer = $this->offerModule->getOffer($offerID)) {
                if (strtotime(date('Y-m-d H:i:s')) > strtotime($offer['offer_end_date'])) {
                    throw new Exception('It cannot be delete because offer is finished!');
                }

                if($wpdb->delete($wpdb->prefix . 'offer_applies', [
                    'offer_id' => $offerID,
                    'user_id' => $userID,
                ])) {
                    // TODO REMCO mail-ek kiküldése
                    return true;
                }


                return false;
            }

        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * A user már jelentkezett az ajánlatra?
     *
     * @param int $userID
     * @param int $offerID
     * @return int
     */
    public function isUserAppliedOffer(int $userID, int $offerID)
    {
        global $wpdb;
        $result = $wpdb->get_results('select count(id) as count from sb_offer_applies WHERE user_id = ' . $userID . ' and offer_id = ' . $offerID);

        return (bool)$result[0]->count;

    }

    /**
     * @param int    $offerID
     * @param bool   $withCustomerData
     * @param string $output
     * @return array|object|null
     */
    public function getAppliesForOffer(int $offerID, bool $withCustomerData = false, $output = ARRAY_A)
    {
        global $wpdb;

        $results = $wpdb->get_results('select * from sb_offer_applies WHERE offer_id = ' . $offerID, $output);

        if (count($results) && $withCustomerData) {
            foreach ($results as &$result) {
                $userID = $output == ARRAY_A ? $result['user_id'] : $result->user_id;
                $result['customer'] = dokan_get_vendor($userID);
            }
        }

        return $results;
    }

    public function getMyOfferAppliesForDashboard()
    {
        global $wpdb;

        $ret = [];
        $results = $wpdb->get_results('select * from sb_offer_applies WHERE user_id = ' . get_current_user_id() . ' order by id desc', ARRAY_A);

        if (count($results)) {
            foreach ($results as &$result) {
                $result['offer'] = $this->offerModule->getOfferData($result['offer_id']);
            }
            $ret = $results;
        }

        return $ret;
    }
}