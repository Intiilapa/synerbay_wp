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
        $this->addAction('getDokanVisibleSelect');
        // offer search
        $this->addAction('getOfferSearchShippingToSelect');
        $this->addAction('getOfferSearchProductCategorySelect');
        $this->addAction('getOfferSearchTransportParitySelect');
        $this->addAction('getOfferSearchVendorSelect');
    }

    public function getDokanMyProductsSelect($selected = false, array $errorMessages = []) {

        /** @var Product $module */
        $module = $this->getModule('product');
        $haystack = $module->getMyProductsForSelect();

        if (!count($haystack)) {
            $haystack = ['' => 'Please create product'];
        }

        $this->generateDokanSelect('product_id', $haystack, 'Product', $selected, $errorMessages, 'Select the product you want to offer');
    }

    public function getDokanMaterialTypesSelect($selectedHaystack = [], array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getMaterialTypes();

        $this->generateDokanMultiSelect('material', $haystack, 'Material', $selectedHaystack, $errorMessages, 'Add material');
    }

    public function getDokanUnitTypesSelect($selected = false, array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getUnitTypes();

        $this->generateDokanSelect('weight_unit_sign', $haystack, 'Unit type', $selected, $errorMessages, '');
    }

    public function getDokanParityTypesSelect($selected = false, array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getOfferTransportParityTypes();

        $this->generateDokanSelect('transport_parity', $haystack, 'Delivery terms', $selected, $errorMessages, 'Choose shipping parity');
    }

    public function getDokanShippingToOfferSelect($selected = false, array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getDeliveryDestinationsForOffer();

        $this->generateDokanMultiSelect('shipping_to', $haystack, 'Shipping to', $selected, $errorMessages, 'Choose the locations you ship to');
    }

    public function getDokanVisibleSelect($selected = false, array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getYesNo();

        $this->generateDokanSelect('visible', $haystack, 'Visible', $selected, $errorMessages, 'Offer visible before start?');
    }

    public function getOfferSearchShippingToSelect($selected = false)
    {
        $haystack = ['' => '-'] + SynerBayDataHelper::getDeliveryDestinationsForOffer();
        $this->generateMartfurySearchSelect('shipping_to', $haystack, 'Shipping to', $selected);
    }

    public function getOfferSearchProductCategorySelect($selected = false)
    {
        $haystack = ['' => '-'] + SynerBayDataHelper::getCategoriesFromDBToSelect();
        $this->generateMartfurySearchSelect('category_id', $haystack, 'Product category', $selected);
    }

    public function getOfferSearchTransportParitySelect($selected = false)
    {
        $haystack = ['' => '-'] + SynerBayDataHelper::getOfferTransportParityTypes();
        $this->generateMartfurySearchSelect('transport_parity', $haystack, 'Delivery terms', $selected);
    }

    public function getOfferSearchVendorSelect($selected = false)
    {
        $haystack = ['' => '-'] + SynerBayDataHelper::getActiveVendorsForOfferSearch();
        $this->generateMartfurySearchSelect('user_id', $haystack, 'Vendor', $selected);
    }
}