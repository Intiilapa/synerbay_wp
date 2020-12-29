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
        $this->addAction('getDokanOfferIDHiddenInput');
    }

    public function getDokanOfferMinimumOrderQTYInput($value = '', array $errorMessages = [])
    {
        $this->getDokanIntegerInput('Minimum order quantity', 'minimum_order_quantity', $value, '', $errorMessages, 'Minimum quantity that needs to be ordered / customer');
    }

    public function getDokanOfferOrderQTYStepInput($value = '', array $errorMessages = [])
    {
        $this->getDokanIntegerInput('Order quantity step', 'order_quantity_step', $value, '', $errorMessages, 'The amount by which the quantity increases when customers add more order need (ex.: One carton contains 10 pieces and minimum packaging quantity is one carton, than add 10 pieces as quantity step, so customers will order only full cartons)');
    }

    public function getDokanOfferMaxTotalOfferQtyInput($value = '', array $errorMessages = [])
    {
        $this->getDokanIntegerInput('Max total offer quantity / customer', 'max_total_offer_qty', $value, '', $errorMessages, 'The maximum limit as much as one customer can order. Leave it empty if you don’t want to limit it.');
    }

    public function getDokanOfferUnitInput($value = '', array $errorMessages = [])
    {
        $this->getDokanIntegerInput('Unit', 'weight_unit', $value, '', $errorMessages, 'Andris - körítő szöveg');
    }

    public function getDokanOfferDeliveryStartDate($value = '', array $errorMessages = [])
    {
        $this->getDokanDateInput('Delivery start date', 'delivery_date', $value, '', $errorMessages, 'Add your possible delivery date');
    }

    public function getDokanOfferStartDate($value = '', array $errorMessages = [])
    {
        $this->getDokanDateInput('Offer start date', 'offer_start_date', $value, '', $errorMessages, 'Add start date for your offer');
    }

    public function getDokanOfferEndDate($value = '', array $errorMessages = [])
    {
        $this->getDokanDateInput('Offer end date', 'offer_end_date', $value, '', $errorMessages, 'Add end date to your offer');
    }

    public function getDokanOfferIDHiddenInput($value = '', array $errorMessages = [])
    {
        $this->getHiddenInput('offer_id', $value, $errorMessages);
    }

    public function getPriceStepInput($value = '', array $errorMessages = [])
    {
        if ($value && !is_array($value) && strlen($value) > 0) {
            $value = json_decode($value, true);
        }

        $stepRowHtmlBody = '
                    Quantity: <input type="text" name="price_step_qty" onkeyup="initPriceStepsForPost()" value="%s" style="width: 100px !important;">
                    Price: <input type="text" name="price_step_price" onkeyup="initPriceStepsForPost()" value="%s" style="width: 100px !important;">
                    <button id="remove-step-row" onclick="deleteStepRow(this)" type="button">-</button>
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
            '<button type="button" id="add-new-empty-row">+</button></div>';

        echo '
            <div class="dokan-form-group">
            <div id="price_step_row_wrapper" style="display: none;">
                    ' . sprintf($stepRowHtmlBody, '', '') . '
            </div>
            <label for="price_steps" class="form-label">Price steps:</label>
            '. $this->setupDescription('Add prices by their quantities
(ex.: 0pcs - 100pcs, $10/piece | 101pcs - 200pcs, $8/piece | 201pcs - 300pcs, $6/piece)') .'
            <input type="hidden" id="priceSteps" name="price_steps" style="height: 0 !important" value="'.json_encode($value).'">
            ' . $input . '
            <br>' . $this->inputError('price_steps', $errorMessages) . '
       </div> 
        ';
    }
}