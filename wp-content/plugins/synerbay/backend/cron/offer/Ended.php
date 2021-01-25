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
use WC_Order;
use WP_User;

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
                            // rendelés létrehozása
                            $this->createOrder($apply, $offer);

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
//                    if ($offer['actual_commission_price'] > 0) {
//                        $this->charge($offer);
//                    }

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

    private function createOrder($applyUser, $offerData)
    {
        /** @var Dokan_Vendor $dokanUser */
        $dokanUser = $applyUser['customer'];

        /** @var WP_User $wcUser */
        $wcUser = $dokanUser->data;
        $shopInfo = $dokanUser->get_shop_info();

        $address = [
            'first_name' => $wcUser->first_name,
            'last_name'  => $wcUser->last_name,
            'company'    => $shopInfo['store_name'],
            'email'      => $wcUser->user_email,
            'phone'      => '',
            'address_1'  => $shopInfo['address']['street_1'],
            'address_2'  => $shopInfo['address']['street_2'],
            'city'       => $shopInfo['address']['city'],
            'state'      => $shopInfo['address']['state'],
            'postcode'   => $shopInfo['address']['zip'],
            'country'    => $shopInfo['address']['country'],
            'vat'        => $shopInfo['vendor_vat'],
        ];

        /** @var WC_Order $order */
        $order = wc_create_order([
            'customer_id' => $wcUser->ID,
        ]);

        $order->add_product($offerData['product']['wc_product'], $applyUser['qty'],
            [
                'subtotal' => $applyUser['qty'] * $offerData['summary']['actual_product_price'],
                'total' => $applyUser['qty'] * $offerData['summary']['actual_product_price'],
            ]);

        $order->set_address($address, 'billing');
        $order->set_address($address, 'shipping');
        $order->set_customer_id($wcUser->ID);
        $order->set_total($applyUser['qty'] * $offerData['summary']['actual_product_price']);
        $order->save();

        $order->calculate_totals();

        dokan_sync_insert_order($order->get_id());

        // Store Order ID in session so it can be re-used after payment failure
//        WC()->session->order_awaiting_payment = $order->get_id();

        return $order;
    }

//    private function charge(FullOfferResource $offer)
//    {
//        return true;
//    }
}