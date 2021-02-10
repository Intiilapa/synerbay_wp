<?php


namespace SynerBay\Forms;


use SynerBay\Forms\Columns\Column;
use SynerBay\Helper\SynerBayDataHelper;

class CreateProduct extends AbstractForm
{
    protected function init()
    {
        $this->addColumn(new Column(
            'weight_unit',
            true,
            ['StringLength' => ['min' => 1, 'max' => 255],],
            ['Trim', 'SetNull'],
        ));

//        $this->addColumn(new Column(
//            'weight_unit_sign',
//            true,
//            ['inArray' => ['haystack' => array_keys(SynerBayDataHelper::getUnitTypes())]],
//        ));

        $this->addColumn(new Column(
            'material',
            true,
            ['inArray' => ['haystack' => array_keys(SynerBayDataHelper::getMaterialTypes())]],
            ['mysqlSet']
        ));
    }
}