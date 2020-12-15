<?php


namespace SynerBay\HTMLElement;


class TextElement extends AbstractElement
{
    protected function init()
    {
        $this->addAction('getDokanOfferMinimumOrderQTYInput');
        $this->addAction('getDokanOfferOrderQTYStepInput');
        $this->addAction('getDokanOfferMaxTotalOfferQtyInput');
        $this->addAction('getDokanOfferUnitInput');
        $this->addAction('getDokanOfferDeliveryStartDate');
        $this->addAction('getDokanOfferStartDate');
        $this->addAction('getDokanOfferEndDate');
        $this->addAction('getPriceStepInput');
    }

    public function getDokanOfferMinimumOrderQTYInput($value = '', array $errorMessages = [])
    {
        $this->getDokanIntegerInput('Minimum order quantity', 'minimum_order_quantity', $value, 5, $errorMessages);
    }

    public function getDokanOfferOrderQTYStepInput($value = '', array $errorMessages = [])
    {
        $this->getDokanIntegerInput('Order quantity step', 'order_quantity_step', $value, 1, $errorMessages);
    }

    public function getDokanOfferMaxTotalOfferQtyInput($value = '', array $errorMessages = [])
    {
        $this->getDokanIntegerInput('Max total offer quantity / customer', 'max_total_offer_qty', $value, '', $errorMessages);
    }

    public function getDokanOfferUnitInput($value = '', array $errorMessages = [])
    {
        $this->getDokanIntegerInput('Unit', 'weight_unit', $value, 0, $errorMessages);
    }

    public function getDokanOfferDeliveryStartDate($value = '', array $errorMessages = [])
    {
        $this->getDokanDateInput('Delivery start date', 'delivery_date', $value, '', $errorMessages);
    }

    public function getDokanOfferStartDate($value = '', array $errorMessages = [])
    {
        $this->getDokanDateInput('Offer start date', 'offer_start_date', $value, '', $errorMessages);
    }

    public function getDokanOfferEndDate($value = '', array $errorMessages = [])
    {
        $this->getDokanDateInput('Offer end date', 'offer_end_date', $value, '', $errorMessages);
    }

    public function getPriceStepInput($value = '', array $errorMessages = [])
    {
        $addElementSkeleton = ['qty' => '', 'price' => ''];

        $input = '
<div id="price_step_row">
                Quantity: <input type="text" name="price_step_qty_wrapper" value="10" style="width: 100px !important;">
                Price: <input type="text" name="price_step_price_wrapper" value="5" style="width: 100px !important;">
                <button type="button" id="delete-wrapper" style="width: 30px !important;height: 30px !important;background: red; color: white; padding-bottom: 10px !important;">-</button>
                </div>
            ';

        if (empty($value)) {
            $input .= '<br><div id="price_step_row">
                Quantity: <input type="text" name="price_step_qty_wrapper" value="" style="width: 100px !important;">
                Price: <input type="text" name="price_step_price_wrapper" value="" style="width: 100px !important;"><br>
                <button data-add-new-price-rule type="button" id="add-new-empty-wrapper" style="width: 30px !important;height: 30px !important;background: green; color: white; padding-bottom: 10px !important;">+</button>
                </div>
            ';
        }

        $input = '<div id="price_step_wrapper">' . $input . '</div>';

        echo '
            <div id="add_price_step_row" style="display: none;">
                Quantity: <input type="text" name="price_step_qty" value="" style="width: 100px !important;">
                Price: <input type="text" name="price_step_price" value="" style="width: 100px !important;"><br>
                <button type="button" id="add-new-empty-wrapper" style="width: 30px !important;height: 30px !important;background: green; color: white; padding-bottom: 10px !important;">+</button>
            </div>

            <div class="dokan-form-group">
            <script type="text/javascript">
                window.addEventListener("load", function() {
                    window.synerbay.initPriceStepInput();
                })
            </script>
            <label for="price_steps" class="form-label">Price steps:</label>
            <input type="hidden" name="price_steps_skeleton" style="height: 0 !important" value="'.json_encode($addElementSkeleton).'">
            <input type="hidden" name="price_steps" style="height: 0 !important" value="'.$value.'">
            ' . $input . '
            <br>' . $this->inputError('price_steps', $errorMessages) . '
       </div> 
        ';
    }
}