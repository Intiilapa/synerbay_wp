<?php

namespace SynerBay\Forms;


use SynerBay\Forms\Columns\Column;
use SynerBay\Helper\SynerBayDataHelper;

class Offer extends AbstractForm
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
            'user_id',
            true,
            [],
            ['integer',],
        ));

        $this->addColumn(new Column(
            'delivery_date',
            true,
        ));

        $this->addColumn(new Column(
            'offer_start_date',
            true,
        ));

        $this->addColumn(new Column(
            'offer_end_date',
            true,
        ));

        $this->addColumn(new Column(
            'price_steps',
            true,
            ['isArray' => []],
        ));

        $this->addColumn(new Column(
            'minimum_order_quantity',
            true,
            [],
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
            [],
            ['integer']
        ));

        $this->addColumn(new Column(
            'weight_unit',
            true,
            [],
            ['integer']
        ));

        $this->addColumn(new Column(
            'weight_unit_sign',
            true,
            ['inArray' => ['haystack' => array_keys(SynerBayDataHelper::getUnitTypes())]],
        ));

        $this->addColumn(new Column(
            'material',
            true,
            ['inArray' => ['haystack' => array_keys(SynerBayDataHelper::getMaterialTypes())]],
            ['mysqlSet']
        ));

        $this->addColumn(new Column(
            'transport_parity',
            true,
            ['inArray' => ['haystack' => array_keys(SynerBayDataHelper::getOfferTransportParityTypes())]],
        ));

        $this->addColumn(new Column(
            'shipping_to',
            true,
            ['stringLength' => ['min' => 3, 'max' => 255]],
            ['trim']
        ));
    }

}