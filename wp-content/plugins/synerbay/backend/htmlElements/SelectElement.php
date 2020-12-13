<?php
namespace SynerBay\HTMLElement;

use SynerBay\Helper\SynerBayDataHelper;
use SynerBay\Module\Product;
use SynerBay\Traits\Loader;

class SelectElement extends AbstractElement
{
    use Loader;

    public function __construct()
    {
        $this->addAction('getMyProductsSelect');
        $this->addAction('getMaterialTypesSelect');
        $this->addAction('getUnitTypesSelect');
        $this->addAction('getParityTypesSelect');
    }

    public function getMyProductsSelect($selected = false) {

        /** @var Product $module */
        $module = $this->getModule('product');
        $haystack = $module->getMyProductsForSelect();

        $this->generateSelect('product_id', $haystack, 'Product', $selected);
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