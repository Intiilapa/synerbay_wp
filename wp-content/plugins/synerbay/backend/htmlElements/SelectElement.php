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
        // store search
        $this->addAction('getVendorSearchIndustrySelect');
        $this->addAction('getVendorSearchCompanyTypeSelect');
        $this->addAction('getVendorSearchDeliveryDestinationsSelect');
        $this->addAction('getVendorSearchAnnualRevenueSelect');
        $this->addAction('getVendorSearchEmployeesSelect');
        $this->addAction('getVendorSearchProductRangeSelect');
        $this->addAction('getVendorSearchRatingSelect');
    }

    public function getDokanMyProductsSelect($selected = false, array $errorMessages = [])
    {

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

    public function getDokanMaterialTypesSelect($selectedHaystack = [], array $errorMessages = [])
    {

        $haystack = SynerBayDataHelper::getMaterialTypes();

        $this->generateDokanMultiSelect('material', $haystack, 'Material', $selectedHaystack, $errorMessages, 'Add material');
    }

    public function getDokanUnitTypesSelect($selected = false, array $errorMessages = [])
    {

        $haystack = SynerBayDataHelper::getUnitTypes();

        $this->generateDokanSelect('weight_unit_sign', $haystack, 'Unit type', $selected, $errorMessages, '');
    }

    public function getDokanParityTypesSelect($selected = false, array $errorMessages = [])
    {

        $haystack = SynerBayDataHelper::getOfferTransportParityTypes();

        $this->generateDokanSelect('transport_parity', $haystack, 'Delivery terms', $selected, $errorMessages, 'Choose shipping parity');
    }

    public function getDokanShippingToOfferSelect($selected = false, array $errorMessages = [])
    {

        $haystack = SynerBayDataHelper::getDeliveryDestinationsForOfferWithCountries();

        $this->generateDokanMultiSelect('shipping_to', $haystack, 'Shipping to', $selected, $errorMessages, 'Choose the locations you ship to');
    }

    public function getDokanVisibleSelect($selected = false, array $errorMessages = [])
    {

        $haystack = SynerBayDataHelper::getYesNo();

        $this->generateDokanSelect('visible', $haystack, 'Visible', $selected, $errorMessages, 'Offer visible before start?');
    }

    public function getDokanCurrencySelect($selected = false, array $errorMessages = [])
    {

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

    // vendor search ...
    public function getVendorSearchIndustrySelect($selected = false)
    {
        $haystack = SynerBayDataHelper::getIndustries();
        $this->generateMartfurySearchSelect('industry', $haystack, 'Industry', $selected);
    }

    public function getVendorSearchCompanyTypeSelect($selected = false)
    {
        $haystack = SynerBayDataHelper::getCompanyTypes();
        $this->generateMartfurySearchSelect('company_type', $haystack, 'Company Type', $selected);
    }

    public function getVendorSearchDeliveryDestinationsSelect($selected = false)
    {
        $haystack = SynerBayDataHelper::getDeliveryDestinations();
        $this->generateMartfurySearchSelect('shipping_to', $haystack, 'Shipping to', $selected);
    }

    public function getVendorSearchAnnualRevenueSelect($selected = false)
    {
        $haystack = SynerBayDataHelper::getRevenues();
        $this->generateMartfurySearchSelect('annual_revenue', $haystack, 'Annual revenue', $selected);
    }

    public function getVendorSearchEmployeesSelect($selected = false)
    {
        $haystack = SynerBayDataHelper::getEmployees();
        $this->generateMartfurySearchSelect('employees', $haystack, 'Employees', $selected);
    }

    public function getVendorSearchProductRangeSelect($selected = false)
    {
        $haystack = SynerBayDataHelper::getProductRanges();
        $this->generateMartfurySearchSelect('product_range', $haystack, 'Product range', $selected);
    }

    public function getVendorSearchRatingSelect($selected = false)
    {
        $haystack = [
            '' => __('Select rating'),
            '1' => __('1+'),
            '2' => __('2+'),
            '3' => __('3+'),
            '4' => __('4+'),
            '5' => __('5'),
        ];
        $this->generateMartfurySearchSelect('rating', $haystack, 'Rating', $selected);
    }
}