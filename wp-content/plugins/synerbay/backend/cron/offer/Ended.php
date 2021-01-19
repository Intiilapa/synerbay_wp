<?php

namespace SynerBay\Cron\Offer;

use Dokan_Vendor;
use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Emails\Service\Offer\Admin\OfferEnded as AdminOfferEnded;
use SynerBay\Emails\Service\Offer\Customer\OfferEnded as CustomerOfferEnded;
use SynerBay\Emails\Service\Offer\Vendor\OfferEnded as VendorOfferEnded;
use SynerBay\Model\Offer;
use SynerBay\Model\OfferApply;
use SynerBay\Repository\OfferRepository;
use SynerBay\Resource\Offer\FullOfferResource;

class Ended extends AbstractCron implements InterfaceCron
{
    public function init()
    {
        add_action('offer_ended_task', [$this, 'run']);
    }

    public function run()
    {
        // init
        $repository = new OfferRepository();
        $resource = new FullOfferResource();

        // adatlekérés
        $offers = $resource->collection($repository->search(['ended' => true, 'status' => [Offer::STATUS_PENDING, Offer::STATUS_STARTED]]));

        // van adat?
        if (count($offers)) {

            // iterálás
            foreach ($offers as $offer) {
                // offer zárása
                if ($repository->changeStatus($offer['id'], Offer::STATUS_CLOSED)) {
                    // customer-ök kiértesítése
                    if (count($offer['applies'])) {
                        $mail = new CustomerOfferEnded($offer);
                        foreach ($offer['applies'] as $apply) {
                            // csak az aktyvokat vesszük figyelembe
                            if ($apply['status'] != OfferApply::STATUS_ACTIVE) {
                                continue;
                            }

                            /** @var Dokan_Vendor $customer */
                            $customer = $apply['customer'];
                            // customer gran total
                            $applicantTotal = (int)$apply['qty'] * (float)$offer['summary']['actual_product_price'];
                            $apply['grand_total'] = wc_price($applicantTotal);
                            unset($apply['customer']);
                            $mail->send($customer->get_name(), $customer->get_email(), $apply);
                        }
                    }
                    // vendor kiértesítése (ha nem járt sikerrel [vagy kevesen vannak, vagy konkrétan 0 ember jelentkezett], akkor segítsünk neki, hogy hívja meg az eddig partnereit, stb)
                    // charge (ha nem 0)
                    if ($offer['actual_commission_price'] > 0) {
                        $this->charge($offer);
                    }

                    // send email to vendor
                    /** @var Dokan_Vendor $vendor */
                    $vendor = $offer['vendor'];
                    (new VendorOfferEnded($offer))->send($vendor->get_name(), $vendor->get_email());

                    // send email to admin
                    $adminMail = new AdminOfferEnded($offer);
                    $adminMail->send('András (admin)', 'kalovicsandras@gmail.com');
                    $adminMail->send('Kristóf (admin)', 'nagy.kristof.janos@gmail.com');
                }
            }
        }
    }

    private function charge(FullOfferResource $offer)
    {
        return true;
    }
}