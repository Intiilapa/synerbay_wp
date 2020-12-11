<?php
namespace SynerBay\HTMLElement;

use SynerBay\Helper\SynerBayDataHelper;

class SelectElement extends AbstractElement
{
    public function __construct()
    {
        $this->addAction('getMaterialTypesSelect');
        $this->addAction('getUnitTypesSelect');
        $this->addAction('getParityTypesSelect');
    }

    public function getMaterialTypesSelect($selectedHaystack = []) {

        $haystack = SynerBayDataHelper::getMaterialTypes();

        $this->generateMultiSelect('material', $haystack, 'Material', $selectedHaystack);
    }

    public function getUnitTypesSelect($selected = false) {

        $haystack = SynerBayDataHelper::getUnitTypes();

        $this->generateSelect('unit_type', $haystack, 'Unit type', $selected);
    }

    public function getParityTypesSelect($selected = false) {

        $haystack = SynerBayDataHelper::getOfferTransportParityTypes();

        $this->generateSelect('parity_type', $haystack, 'Parity type', $selected);
    }
}