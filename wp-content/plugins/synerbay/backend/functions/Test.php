<?php

namespace SynerBay\Functions;

use SynerBay\Module\Offer;
use SynerBay\Traits\WPActionLoader;

class Test
{
    use WPActionLoader;

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
                'minimum_order_quantity' => 35,
                'order_quantity_step'    => 5,
                'max_total_offer_qty'    => null,
                'weight_unit'            => 10,
                'weight_unit_sign'       => 'mg',
                'material'               => 'wood',
                // set megadása
//                'material'               => 'wood,metal',
                'transport_parity'       => 'exw',
                'shipping_to'            => 'worldwide',
            ]
        );
        print '<pre>';
        var_dump($module->getOfferCurrentData($offerID, true));die;
    }
}