<?php
namespace SynerBay\Module;

use Dokan_Vendor;
use Exception;
use SynerBay\Emails\Service\Offer\Customer\ApplyAccepted;
use SynerBay\Emails\Service\Offer\Customer\ApplyDenied;
use SynerBay\Emails\Service\Offer\Vendor\ApplyCreated;
use SynerBay\Emails\Service\Offer\Vendor\ApplyModified;
use SynerBay\Emails\Service\Offer\Customer\ApplyCreated as CustomerApplyCreated;
use SynerBay\Emails\Service\Offer\Customer\ApplyModified as CustomerApplyModified;
use SynerBay\Repository\OfferApplyRepository;
use SynerBay\Repository\OfferRepository;
use SynerBay\Resource\AbstractResource;
use SynerBay\Resource\Offer\DefaultOfferResource;
use SynerBay\Resource\OfferApply\DefaultOfferApplyResource;
use SynerBay\Resource\OfferApply\FullOfferApplyResource;
use SynerBay\Resource\OfferApply\OfferApplyResourceWithCustomer;
use SynerBay\Traits\Loader;
use SynerBay\Traits\Toaster;

class OfferApply extends AbstractModule
{
    use Loader, Toaster;
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
            if ($offer = $this->offerModule->getOfferData($offerID, true, true, true)) {
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
                    // send mails
                    // a jelentkezőnek
                    /** @var Dokan_Vendor $customer */
                    $customer = dokan_get_vendor($userID);
                    $vendorMail = new CustomerApplyCreated($offer);
                    $vendorMail->send($customer->get_name(), $customer->get_email());

                    // az eddig jelentkezők értesítése
                    if (count($offer['applies'])) {
                        $vendorOfferModifiedMail = new CustomerApplyModified($offer);

                        foreach ($offer['applies'] as $applyUser) {
                            /** @var Dokan_Vendor $user */
                            $user = $applyUser['customer'];
                            $vendorOfferModifiedMail->send($user->get_name(), $user->get_email());
                        }
                    }

                    /**
                     * az eladónak
                     */
                    /** @var Dokan_Vendor $vendor */
                    $vendor = $offer['vendor'];
                    $vendorMail = new ApplyCreated($offer);
                    $vendorMail->send($vendor->get_name(), $vendor->get_email());
                    return true;
                }

                throw new Exception('Unable to create, please try again!');
            }
            throw new Exception('Error! Invalid offer id! Offer not found with id: '.$offerID.'!');
        } catch (Exception $e) {
            $this->addErrorToast($e->getMessage());
            $this->addErrorMsg($e->getMessage());
        }

        return false;
    }

    public function deleteAppearOfferForUser(int $userID, int $offerID)
    {
        global $wpdb;

        try {
            if ($offer = $this->offerModule->getOfferData($offerID, true, true, true)) {
                if (strtotime(date('Y-m-d H:i:s')) > strtotime($offer['offer_end_date'])) {
                    throw new Exception('It cannot be delete because offer is finished!');
                }

                if($wpdb->delete($wpdb->prefix . 'offer_applies', [
                    'offer_id' => $offerID,
                    'user_id' => $userID,
                ])) {
                    // send mails
                    // az eddig jelentkezők értesítése
                    if (count($offer['applies'])) {
                        $customerOfferModifiedMail = new CustomerApplyModified($offer);

                        foreach ($offer['applies'] as $applyUser) {
                            if ($applyUser['user_id'] == $userID) {
                                continue;
                            }

                            /** @var Dokan_Vendor $customer */
                            $customer = $applyUser['customer'];
                            $customerOfferModifiedMail->send($customer->get_name(), $customer->get_email());
                        }
                    }

                    /**
                     * az eladónak
                     */
                    /** @var Dokan_Vendor $vendor */
                    $vendor = $offer['vendor'];
                    $vendorMail = new ApplyModified($offer);
                    $vendorMail->send($vendor->get_name(), $vendor->get_email());

                    return true;
                }

                return false;
            }

        } catch (Exception $e) {
            $this->addErrorToast($e->getMessage());
            $this->addErrorMsg($e->getMessage());
            return false;
        }

        return false;
    }

    public function accept(int $id)
    {
        global $wpdb;

        if ($offerApplyRow = (new OfferApplyRepository())->getRowByPrimaryKey($id)) {

            $offer = (new DefaultOfferResource())->toArray(
                (new OfferRepository())->getRowByPrimaryKey($offerApplyRow['offer_id'])
            );

            if ($offer['user_id'] != get_current_user_id()) {
                return false;
            }

            if ($wpdb->update(
                $wpdb->prefix . 'offer_applies',
                ['status' => \SynerBay\Model\OfferApply::STATUS_ACTIVE],
                ['id' => $id],
                ['%s'],
                ['%d']
            )) {
                /** @var Dokan_Vendor $customer */
                $customer = dokan_get_vendor($offerApplyRow['user_id']);
                $vendorMail = new ApplyAccepted($offer);
                $vendorMail->send($customer->get_name(), $customer->get_email());
                return true;
            }
        }

        return false;
    }

    public function reject(int $id, string $reason = '')
    {
        global $wpdb;

        if ($offerApplyRow = (new OfferApplyRepository())->getRowByPrimaryKey($id)) {
            $offer = (new DefaultOfferResource())->toArray(
                (new OfferRepository())->getRowByPrimaryKey($offerApplyRow['offer_id'])
            );

            if ($offer['user_id'] != get_current_user_id()) {
                return false;
            }

            if ($wpdb->update(
                $wpdb->prefix . 'offer_applies',
                ['status' => \SynerBay\Model\OfferApply::STATUS_DENIED],
                ['id' => $id],
                ['%s'],
                ['%d']
            )) {
                /** @var Dokan_Vendor $customer */
                $customer = dokan_get_vendor($offerApplyRow['user_id']);
                $vendorMail = new ApplyDenied(array_merge($offer, ['reason' => $reason]));
                $vendorMail->send($customer->get_name(), $customer->get_email());
                return true;
            }
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
     * @return array|object|null
     */
    public function getAppliesForOffer(int $offerID, bool $withCustomerData = false)
    {
        $searchParams = [
            'offer_id' => $offerID,
            'order' => [
                'columnName' => 'id',
                'direction' => 'desc'
            ]
        ];

        /** @var AbstractResource $resource */
        $resource = $withCustomerData ? new OfferApplyResourceWithCustomer() : new DefaultOfferApplyResource();

        return $resource->collection((new OfferApplyRepository())->search($searchParams));
    }

    public function getMyOfferAppliesForDashboard()
    {
        $searchParams = [
            'user_id' => get_current_user_id(),
            'order' => [
                'columnName' => 'id',
                'direction' => 'desc'
            ]
        ];

        return (new FullOfferApplyResource())->collection((new OfferApplyRepository())->search($searchParams));
    }
}