<?php

namespace SynerBay\HTMLElement;

use SynerBay\Traits\WPAction;

abstract class AbstractElement
{
    use WPAction;

    public function __construct()
    {
        $this->addAction('input_error_render', 'inputError');

        $this->init();
    }

    protected function init() {}

    protected function generateModal($name, $title, $content)
    {
//        echo '
//            <div id="mf-quick-view-modal" class="mf-quick-view-modal martfury-modal woocommerce" aria-hidden="true">
//            <div class="mf-modal-overlay"></div>
//                <div class="modal-content">
//                <div class="modal-header" style="padding-left: 20px;">
//                    <h5 class="modal-title">'.$title.'</h5>
//                </div>
//                <a href="#" class="close-modal">
//                    <i class="icon-cross"></i>
//                </a>
//                <div class="product-modal-content" style="margin-bottom: 30px;">
//                    '. $content .'
//                </div>
//                </div>
//            <div class="mf-loading"></div>
//            </div>
//        ';

        echo '
            <div id="mf-'. $name .'-popup" class="martfury-modal mf-'.$name.'-popup mf-newsletter-popup woocommerce" tabindex="-1" aria-hidden="true">
                <div class="mf-modal-overlay"></div>
                <div class="modal-content">
                <div class="modal-header" style="padding-left: 20px;">
                    <h5 class="modal-title">'.$title.'</h5>
                </div>
                <a href="#" class="close-modal">
                    <i class="icon-cross"></i>
                </a>
                <div class="newletter-content" style="margin-bottom: 30px; margin-top: -30px;">
                        '. $content .'
                </div>
                </div>
            </div>
        ';
    }

    protected function generateDokanSelect($name, array $haystack, string $label = '', $selected = false, array $errorMessages = [], string $description = '')
    {
        $skeleton = '<select class="dokan-form-control" name="'.$name.'">%s</select>%s</div>';

        if (!empty($label)) {
            $skeleton = '<label for="'.$name.'" class="form-label">'.$label.':</label>' . $this->setupDescription($description) .$skeleton;
        }

        $skeleton = '<div class="dokan-form-group">' . $skeleton;

        $options = '';
        $optionSkeleton = '<option value="%s"%s>%s</option>';

        foreach ($haystack as $value => $label) {
            $options .= sprintf($optionSkeleton, $value, $selected && $value == $selected ? ' selected' : '', $label);
        }

        echo sprintf($skeleton, $options, $this->inputError($name, $errorMessages));
    }

    protected function generateDokanMultiSelect($name, array $haystack, string $label = '', $selectedHaystack = false, array $errorMessages = [], string $description = '')
    {
        if ($selectedHaystack && (!is_array($selectedHaystack) || !count($selectedHaystack))) {
            throw new \Exception('Invalid data in selected haystack, only array allowed!');
        }

        $skeleton = '<select class="dokan-form-control" style="height: 150px !important;" name="'.$name.'[]" multiple>%s</select>%s</div>';

        if (!empty($label)) {
            $skeleton = '<label for="'.$name.'" class="form-label">'.$label.':</label>' . $this->setupDescription($description) .$skeleton;
        }

        $skeleton = '<div class="dokan-form-group">' . $skeleton;

        $options = '';
        $optionSkeleton = '<option value="%s"%s>%s</option>';

        // rakjuk előre ha már valami kivan választva
        $processedValues = [];

        foreach ($haystack as $value => $label) {
            if ($selectedHaystack && count($selectedHaystack) && in_array($value, $selectedHaystack)) {
                $processedValues[] = $value;
                $options .= sprintf($optionSkeleton, $value, ' selected', $label);
            }
        }

        foreach ($haystack as $value => $label) {
            if (!in_array($value, $processedValues)) {
                $options .= sprintf($optionSkeleton, $value, '', $label);
            }
        }

        echo sprintf($skeleton, $options, $this->inputError($name, $errorMessages));
    }

    protected function getDokanIntegerInput(string $label, string $name, $value = '', $placeholder = '', array $errorMessages = [], string $description = '')
    {
        echo '
           <div class="dokan-form-group">
                <label for="'.$name.'" class="form-label">' . $label . ':</label>
                ' . $this->setupDescription($description) . '
                <input type="number" class="dokan-form-control dokan-product" name="'.$name.'" placeholder="' .$placeholder. '" id="input_'.$name.'" value="' . $value . '">
                ' . $this->inputError($name, $errorMessages) . '
           </div> 
        ';
    }

    protected function getDokanFloatInput(string $label, string $name, $value = '', $placeholder = '', array $errorMessages = [], string $description = '')
    {
        echo '
           <div class="dokan-form-group">
                <label for="'.$name.'" class="form-label">' . $label . ':</label>
                ' . $this->setupDescription($description) . '
                <input type="number" step="0.01" class="dokan-form-control dokan-product" name="'.$name.'" placeholder="' .$placeholder. '" id="input_'.$name.'" value="' . $value . '">
                ' . $this->inputError($name, $errorMessages) . '
           </div> 
        ';
    }

    protected function getDokanDateInput(string $label, string $name, $value = '', string $placeholder = '', array $errorMessages = [], string $description = '')
    {
        $placeholder = "dd/mm/yyyy";

        echo '
           <div class="dokan-form-group">
                <label for="'.$name.'" class="form-label">' . $label . ':</label>
                ' . $this->setupDescription($description) . '
                <input type="date" class="dokan-form-control" name="'.$name.'" placeholder="' .$placeholder. '" pattern="(^(((0[1-9]|1[0-9]|2[0-8])[\/](0[1-9]|1[012]))|((29|30|31)[\/](0[13578]|1[02]))|((29|30)[\/](0[4,6,9]|11)))[\/](19|[2-9][0-9])\d\d$)|(^29[\/]02[\/](19|[2-9][0-9])(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)$)" id="input_'.$name.'" value="' . $value . '">
                ' . $this->inputError($name, $errorMessages) . '
           </div> 
        ';
    }

    protected function getDokanTextInput(string $label, string $name, $value = '', string $placeholder = '', array $errorMessages = [], string $description = '')
    {
        echo '
            <div class="dokan-form-group">
                <label for="product_name" class="form-label">' . $label . ':</label>
                ' . $this->setupDescription($description) . '
                <input class="dokan-form-control" name="'.$name.'" id="input_'.$name.'" type="text" placeholder="' .$placeholder. '" value="' . $value . '">
                ' . $this->inputError($name, $errorMessages) . '
            </div>
        ';
    }

    protected function getHiddenInput(string $name, $value = '', array $errorMessages = [])
    {
        echo '<input type="hidden" name="'.$name.'" id="input_'.$name.'" value="' . $value . '">
                ' . $this->inputError($name, $errorMessages);
    }

    protected function setupDescription(string $description = '')
    {
        return !empty($description) ? '<br><span style="margin-top: 3px; font-size: 12px;">'.$description.'</span><br>' : '';
    }

    protected function generateMartfurySearchSelect($name, array $haystack, $label, $selected = false)
    {
        $skeleton = '<li class="dokan-form-group"><select class="dokan-form-control" name="'.$name.'">%s</select></li>';

        if (!empty($label)) {
            $skeleton = '<label for="'.$name.'" class="form-label">'.$label.':</label>' .$skeleton;
        }

        $options = '';
        $optionSkeleton = '<option value="%s"%s>%s</option>';

        foreach ($haystack as $value => $label) {
            $options .= sprintf($optionSkeleton, $value, $selected && $value == $selected ? ' selected' : '', $label);
        }

        echo sprintf($skeleton, $options);
    }

    protected function inputError($name, $errors)
    {
        if (array_key_exists($name, $errors)) {
            return '<span style="margin-top: 3px; font-size: 11px; color: red;">*'.$errors[$name].'</span>';
        }

        return '';
    }
}