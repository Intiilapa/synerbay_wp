<?php
namespace SynerBay\Module;

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
        $table = $wpdb->prefix . 'offer_applies';
        $data = [
            'user_id'  => $userID,
            'offer_id' => $offerID,
            'qty'      => $productQuantity,
        ];
        $format = ['%d', '%d', '%d'];

        try {
            $wpdb->insert($table, $data, $format);
        } catch (Exception $e) {
            return false;
        }

        // TODO mail-ek kiküldése
//        $offerUsers = $this->offerModule->getOfferUsers($offerID);
//
//        foreach ($offerUsers as $offerUser) {
//            if ($offerUser->ID === $userID) {
//                // akkor a usernek megy egy köszönöő mail
//            } else {
//                $this->sendMail($offerUser);
//            }
//        }

        return $this->offerModule->getOfferData($offerID);
    }

    public function deleteAppearOfferForUser(int $userID, int $offerID)
    {
        global $wpdb;

        try {
            $wpdb->delete($wpdb->prefix . 'offer_applies', [
                'offer_id' => $offerID,
                'user_id' => $userID,
            ]);
        } catch (Exception $e) {
            return false;
        }

        // TODO mail-ek kiküldése
        return $this->offerModule->getOfferData($offerID);
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