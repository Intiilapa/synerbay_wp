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
        $this->addAction('getDokanCurrencySelect');
        // offer search
        $this->addAction('getOfferSearchShippingToSelect');
        $this->addAction('getOfferSearchProductCategorySelect');
        $this->addAction('getOfferSearchTransportParitySelect');
        $this->addAction('getOfferSearchVendorSelect');
        $this->addAction('getOfferSearchCurrencySelect');
    }

    public function getDokanMyProductsSelect($selected = false, array $errorMessages = []) {

        /** @var Product $module */
        $module = $this->getModule('product');
        $haystack = $module->getMyProductsForSelect();

        if (!$selected && isset($_GET['product-id'])) {
            $selected = $_GET['product-id'];
        }

        if (!count($haystack)) {
            $haystack = ['' => 'Please create product'];
        }

        $this->generateDokanSelect('product_id', $haystack, 'Product', $selected, $errorMessages, 'Select the product you want to offer.<br>If you did not add any product yet, click catalogue » add new product');
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

        $haystack = SynerBayDataHelper::getDeliveryDestinationsForOfferWithCountries();

        $this->generateDokanMultiSelect('shipping_to', $haystack, 'Shipping to', $selected, $errorMessages, 'Choose the locations you ship to');
    }

    public function getDokanVisibleSelect($selected = false, array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getYesNo();

        $this->generateDokanSelect('visible', $haystack, 'Visible', $selected, $errorMessages, 'Offer visible before start?');
    }

    public function getDokanCurrencySelect($selected = false, array $errorMessages = []) {

        $haystack = SynerBayDataHelper::getLatestCurrenciesForSelect();

        $this->generateDokanSelect('currency', $haystack, 'Currency', $selected, $errorMessages, '');
    }

    public function getOfferSearchShippingToSelect($selected = false)
    {
        $haystack = ['' => '-'] + SynerBayDataHelper::getDeliveryDestinationsForOfferWithCountries();
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

    public function getOfferSearchCurrencySelect($selected = false)
    {
        $haystack = ['' => '-'] + SynerBayDataHelper::getLatestCurrenciesForSelect();
        $this->generateMartfurySearchSelect('cur', $haystack, 'Currency', $selected);
    }
}