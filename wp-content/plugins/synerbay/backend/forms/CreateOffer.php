<?php

namespace SynerBay\Forms;


use SynerBay\Forms\Columns\Column;
use SynerBay\Helper\SynerBayDataHelper;

class CreateOffer extends AbstractForm
{
    protected function init()
    {
        $this->addColumn(new Column(
            'product_id',
            true,
            ['ProductExists' => ['post_author' => get_current_user_id()],],
            ['integer',],
        ));

        $this->addColumn(new Column(
            'payment_term',
            true,
            ['StringLength' => ['min' => 3, 'max' => 255],],
            ['Trim', 'SetNull'],
        ));

        $this->addColumn(new Column(
            'offer_start_date',
            true,
            [
                'date' => [],
            ],
            ['startDay']
        ));

        $this->addColumn(new Column(
            'offer_end_date',
            true,
            [
                'date'                  => [],
                'dateGreaterThenColumn' => ['column' => 'offer_start_date'],
            ],
            ['endDay']
        ));

        $this->addColumn(new Column(
            'delivery_date',
            true,
            [
                'date'                  => [],
                'dateGreaterThenColumn' => ['column' => 'offer_end_date'],
            ],
            ['startDay']
        ));

        $this->addColumn(new Column(
            'price_steps',
            true,
            [
                'isArray'   => [],
                'priceStep' => [],
            ],
            ['priceStep', 'setJSON']
        ));

        $this->addColumn(new Column(
            'minimum_order_quantity',
            true,
            [
                'integer' => []
            ],
            ['integer']
        ));

        $this->addColumn(new Column(
            'order_quantity_step',
            true,
            ['integer' => []],
            ['integer'],
        ));

        $this->addColumn(new Column(
            'max_total_offer_qty',
            false,
            [
                'integer' => [],
                'numericGreaterThenColumn' => ['column' => 'minimum_order_quantity'],
            ],
            ['integer']
        ));

        $this->addColumn(new Column(
            'transport_parity',
            true,
            ['inArray' => ['haystack' => array_keys(SynerBayDataHelper::getOfferTransportParityTypes())]],
        ));

        $this->addColumn(new Column(
            'shipping_to',
            true,
            ['inArray' => ['haystack' => array_keys(SynerBayDataHelper::getDeliveryDestinationsForOffer())]],
            ['setJSON']
        ));

        $this->addColumn(new Column(
            'default_price',
            true,
            [
                'numeric' => [],
            ],
        ));

        $this->addColumn(new Column(
            'visible',
            true,
            ['inArray' => ['haystack' => array_keys(SynerBayDataHelper::getYesNo())]],
        ));
    }

}