<?php

namespace SynerBay\Module;

use SynerBay\Traits\Loader;
use SynerBay\Traits\Module as ModuleTrait;
use Exception;
use WC_Order;
use WeDevs\Dokan\Vendor\Vendor;
use WP_User;

class Order
{
    use ModuleTrait, Loader;

    private Offer $offerModule;

    public function __construct()
    {
        $this->offerModule = $this->getModule('offer');
    }

    public function createOrdersFromOffer($offerID)
    {
        if ($offerData = $this->offerModule->getOfferData($offerID, true, true, true, true)) {
            // nincs jelentekző?
            if (!count($offerData['applies'])) {
                // offer off
                return true;
            }

            // create orders
            foreach ($offerData['applies'] as $applyUser) {
//                var_dump($applyUser);die;
                // INFO a dokan így adja vissza!
                $this->createOrder($applyUser, $offerData);
            }

            // create invoice for offer vendor
            if ($offerData['summary']['actual_commission_price'] > 0) {

            }
//            var_dump($offerData['vendor']);
            die;
        }

        return false;
    }

    private function createOrder($applyUser, array $offerData)
    {
//        var_dump($user->get_shop_info());
//        var_dump($offerData);
//        die;
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

//        var_dump($wcUser->ID);die;

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

//        update_post_meta( $order->get_id(), '_payment_method', 'ideal' );
//        update_post_meta( $order->get_id(), '_payment_method_title', 'iDeal' );

        // Store Order ID in session so it can be re-used after payment failure
        WC()->session->order_awaiting_payment = $order->get_id();

        // Process Payment
//        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
//        $result = $available_gateways[ 'ideal' ]->process_payment( $order->get_id() );

        // Redirect to success/confirmation/payment page
//        if ( $result['result'] == 'success' ) {

//            $result = apply_filters( 'woocommerce_payment_successful_result', $result, $order->get_id() );

//            wp_redirect( $result['redirect'] );
//            exit;
//        }

//        var_dump($applyUser);
//        var_dump($offerData);
//        die;
    }

    /**
     * @param int    $orderID
     * @param string $output
     * @return array|object|void|null
     */
    public function getOrder(int $orderID, $output = ARRAY_A)
    {
//        global $wpdb;
//        return $wpdb->get_row('select * from '.$wpdb->prefix.'offers where id = ' . $offerID, $output);
    }
}