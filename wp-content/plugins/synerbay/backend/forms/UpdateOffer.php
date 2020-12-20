<?php


namespace SynerBay\Forms;


use SynerBay\Forms\Columns\Column;

class UpdateOffer extends CreateOffer
{
    protected function init()
    {
        $this->addColumn(new Column(
            'offer_id',
            true
        ));

        parent::init(); // TODO: Change the autogenerated stub
    }
}