<?php

namespace SynerBay\Cron\Offer;

use Dokan_Vendor;
use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Emails\Service\Offer\Admin\OfferStarted;
use SynerBay\Emails\Service\Offer\Customer\FollowerOfferStarted;
use SynerBay\Emails\Service\Offer\Customer\RFQUserOfferStarted;
use SynerBay\Model\Offer;
use SynerBay\Repository\OfferRepository;
use SynerBay\Repository\RFQRepository;
use SynerBay\Repository\VendorRepository;
use SynerBay\Resource\Offer\OfferStartedResource;

class Started extends AbstractCron implements InterfaceCron
{
    public function init()
    {
        add_action('offer_started_task', [$this, 'run']);
    }

    public function run()
    {
        // init
        $offerRepository = new OfferRepository();
        $vendorRepository = new VendorRepository();
        $rfqRepository = new RFQRepository();
        $resource = new OfferStartedResource();

        // adatlekérés
        $offers = $resource->collection($offerRepository->search(['started' => true, 'status' => [Offer::STATUS_PENDING]]));

        // van adat?
        if (count($offers)) {

            // iterálás
            foreach ($offers as $offer) {
                // offer zárása
                if ($offerRepository->changeStatus($offer['id'], Offer::STATUS_STARTED)) {
                    $followers = $vendorRepository->getFollowers($offer['user_id']);
                    // követők kiértesítése
                    if (count($followers)) {
                        $followerMail = new FollowerOfferStarted($offer);
                        foreach ($followers as $follower) {
                            /** @var Dokan_Vendor $user */
                            $user = $this->getVendor($follower['follower_id']);
                            $followerMail->send($user->get_name(), $user->get_email());
                        }
                    }

                    // rfq userek kiértesítése
                    $rfqUsers = $rfqRepository->search(['product_id' => $offer['product_id']]);
                    if (count($rfqUsers)) {
                        $rfqMail = new RFQUserOfferStarted($offer);
                        foreach ($rfqUsers as $rfqUser) {
                            /** @var Dokan_Vendor $user */
                            $user = $this->getVendor($rfqUser['user_id']);
                            $rfqMail->send($user->get_name(), $user->get_email(), $rfqUser);
                        }

                        // töröljük az rfq-kat
                        $rfqRepository->deleteProductRFQ($offer['product_id']);
                    }

                    // adminok kiértesítése
                    $adminMail = new OfferStarted($offer);
                    $adminMail->send('András (admin)', 'kalovicsandras@gmail.com');
                    $adminMail->send('Kristóf (admin)', 'nagy.kristof.janos@gmail.com');
                }
            }
        }
    }
}