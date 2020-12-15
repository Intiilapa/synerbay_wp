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
        if ($value && !is_array($value) && strlen($value) > 0) {
            $value = json_decode($value, true);
        }

        $stepRowHtmlBody = '
                    Quantity: <input type="text" name="price_step_qty" onkeyup="initPriceStepsForPost()" value="%s" style="width: 100px !important;">
                    Price: <input type="text" name="price_step_price" onkeyup="initPriceStepsForPost()" value="%s" style="width: 100px !important;">
                    <button id="remove-step-row" onclick="deleteStepRow(this)" type="button" style="width: 30px !important;height: 30px !important;background: red; color: white; padding-bottom: 10px !important;">-</button>
        ';

        $valueSteps = '';

        if (empty($value)) {
            $valueSteps .= '
                <div id="price_step_row">
                    ' . sprintf($stepRowHtmlBody, '', '') . '
                </div>
            ';
        } else {
            foreach ($value as $step) {
                $valueSteps .= '
                <div id="price_step_row">
                    ' . sprintf($stepRowHtmlBody, $step['qty'], $step['price']) . '
                </div>
            ';
            }
        }

        $input = '<div id="price_step_wrapper">'
            . $valueSteps .
            '<button type="button" id="add-new-empty-row" style="width: 30px !important;height: 30px !important;background: green; color: white; padding-bottom: 5px !important; margin-top: 10px;">+</button></div>';

        echo '
            <div class="dokan-form-group">
            <div id="price_step_row_wrapper" style="display: none;">
                    ' . sprintf($stepRowHtmlBody, '', '') . '
            </div>
            <label for="price_steps" class="form-label">Price steps:</label>
            <input type="hidden" id="priceSteps" name="price_steps" style="height: 0 !important" value="'.json_encode($value).'">
            ' . $input . '
            <br>' . $this->inputError('price_steps', $errorMessages) . '
       </div> 
        ';
    }
}