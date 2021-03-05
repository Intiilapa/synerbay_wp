<?php

namespace SynerBay\Module;

use Dokan_Vendor;
use Exception;
use SynerBay\Emails\Product\CreateRFQVendor;
use SynerBay\Repository\RFQRepository;
use SynerBay\Traits\Module as ModuleTrait;

class RFQ extends AbstractModule
{
    use ModuleTrait;

    private $dataMap = [
        'product_id' => '%d',
        'user_id'    => '%d',
        'qty'        => '%d',
    ];

    /**
     * @param int $userID
     * @param int $productID
     * @param int $qty
     * @return bool
     * @throws Exception
     */
    public function create(int $userID, int $productID, int $qty)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'rfq';

        $data = [
            'user_id' => $userID,
            'product_id' => $productID,
            'qty' => $qty,
        ];

        try {
            $wpdb->insert($table, $this->cleanData($data), $this->getInsertFormat());
            $lastInsertedID = $wpdb->insert_id;

            // todo email
            $post = get_post($productID);
            /** @var Dokan_Vendor $vendor */
            $vendor = dokan_get_vendor($post->post_author);
            $mail = new CreateRFQVendor($data);
            $mail->send($vendor->get_name(), $vendor->get_email());
        } catch (Exception $e) {
            return false;
        }

        return $lastInsertedID;
    }

    /**
     * @param int $rfqID
     * @param int $userID
     * @return bool
     */
    public function delete(int $rfqID, int $userID)
    {
        $repository = new RFQRepository();
        if ($rfq = $repository->getRFQById($rfqID)) {
            if ($rfq['user_id'] != $userID) {
                return false;
            }

            return $repository->delete(['id' => $rfqID]);
        }

        return true;
    }
}