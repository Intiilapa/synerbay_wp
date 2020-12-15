<?php
namespace SynerBay\HTMLElement;

use SynerBay\Helper\SynerBayDataHelper;
use SynerBay\Module\Product;
use SynerBay\Traits\Loader;

class SelectElement extends AbstractElement
{
    use Loader;

    public function init()
    {
        $this->addAction('getDokanMyProductsSelect');
        $this->addAction('getDokanMaterialTypesSelect');
        $this->addAction('getDokanUnitTypesSelect');
        $this->addAction('getDokanParityTypesSelect');
        $this->addAction('getDokanShippingToOfferSelect');
    }

    public function getDokanMyProductsSelect($selected = false, array $errorMessages = []) {

        /** @var Product $module */
        $module = $this->getModule('product');
        $haystack = $module->getMyProductsForSelect();

        $this->generateDokanSelect('product_id', $haystack, 'Product', $selected, $errorMessages);
    }

    public function getDokanMaterialTypesSelect($selectedHaystack = [], array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getMaterialTypes();

        $this->generateDokanMultiSelect('material', $haystack, 'Material', $selectedHaystack, $errorMessages);
    }

    public function getDokanUnitTypesSelect($selected = false, array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getUnitTypes();

        $this->generateDokanSelect('weight_unit_sign', $haystack, 'Unit type', $selected, $errorMessages);
    }

    public function getDokanParityTypesSelect($selected = false, array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getOfferTransportParityTypes();

        $this->generateDokanSelect('transport_parity', $haystack, 'Parity type', $selected, $errorMessages);
    }

    public function getDokanShippingToOfferSelect($selected = false, array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getDeliveryDestinationsForOffer();

        $this->generateDokanMultiSelect('shipping_to', $haystack, 'Shipping to', $selected, $errorMessages);
    }
}