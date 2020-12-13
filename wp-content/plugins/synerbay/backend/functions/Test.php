<?php

namespace SynerBay\Functions;

use SynerBay\Module\Offer;
use SynerBay\Module\OfferApply;
use SynerBay\Module\Order;
use SynerBay\Traits\Loader;
use SynerBay\Traits\WPAction;

class Test
{
    use WPAction, Loader;

    public function __construct()
    {
        $this->addAction('test');
    }

    public function test()
    {
        /** @var Offer $module */
        $module = $this->getModule('offer');

        $offerID = $module->createOffer([
                'product_id'             => 36,
                'delivery_date'          => date('Y-m-d H:i:s'),
                'offer_start_date'       => date('Y-m-d H:i:s', strtotime('+1 days', time())),
                'offer_end_date'         => date('Y-m-d H:i:s', strtotime('+45 days', time())),
                'price_steps'            => [
                    [
                        'qty' => 5,
                        'price' => 50,
                    ],
                    [
                        'qty' => 10,
                        'price' => 45,
                    ],
                    [
                        'qty' => 15,
                        'price' => 40,
                    ],
                    [
                        'qty' => 20,
                        'price' => 38,
                    ],
                    [
                        'qty' => 28,
                        'price' => 37,
                    ],
                    [
                        'qty' => 30,
                        'price' => 35,
                    ],
                    [
                        'qty' => 35,
                        'price' => 30,
                    ],
                    [
                        'qty' => 40,
                        'price' => 28,
                    ],
                    [
                        'qty' => 45,
                        'price' => 26,
                    ],
                    [
                        'qty' => 50,
                        'price' => 24,
                    ],
                    [
                        'qty' => 55,
                        'price' => 20,
                    ],
                    [
                        'qty' => 60,
                        'price' => 10,
                    ],
                ],
                'minimum_order_quantity' => 35,
                'order_quantity_step'    => 5,
//                'max_total_offer_qty'    => null,
                'max_total_offer_qty'    => 1000,
                'weight_unit'            => 10,
                'weight_unit_sign'       => 'mg',
//                'material'               => ['wood'],
                // adatbázis set megadására példa, az eggyel feljebb lévő sort is beveszi
                'material'               => [
                        'wood',
                        'metal'
                    ],
                'transport_parity'       => 'exw',
                'shipping_to'            => 'worldwide',
            ]
        );

        /** @var OfferApply $offerApplyModule */
        $offerApplyModule = $this->getModule('offerApply');

        $offerApplyModule->createAppearOfferForUser(1, $offerID, rand(5, 30));
        $offerApplyModule->createAppearOfferForUser(14, $offerID, rand(5, 40));

        print '<pre>';
        //var_dump($module->getOfferData($offerID));

        // update offer example
//        $offerID = $module->updateOffer($offerID, [
//            'delivery_date'          => date('Y-m-d H:i:s', strtotime('+150 days', time())),
//            'offer_start_date'       => date('Y-m-d H:i:s', strtotime('+1 days', time())),
//            'offer_end_date'         => date('Y-m-d H:i:s', strtotime('+45 days', time())),
//            'price_steps'            => [
//                [
//                    'qty' => 5,
//                    'price' => 50,
//                ],
//                [
//                    'qty' => 10,
//                    'price' => 5,
//                ],
//            ],
//        ]);

        print '<pre>';
        var_dump($module->getOfferData($offerID));

        /** @var Order $orderModule */
//        $orderModule = $this->getModule('order');
//        $orderModule->createOrdersFromOffer($offerID);

        die;
    }
}