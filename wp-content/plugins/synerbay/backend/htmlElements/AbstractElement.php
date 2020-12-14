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
        echo '
            <div id="mf-'. $name .'-popup" class="martfury-modal mf-'.$name.'-popup mf-newsletter-popup" tabindex="-1" aria-hidden="true">
                <div class="mf-modal-overlay"></div>
                <div class="modal-content">
                <div class="modal-header" style="padding-left: 20px;">
                    <h5 class="modal-title">'.$title.'</h5>
                </div>
                <a href="#" class="close-modal">
                    <i class="icon-cross"></i>
                </a>
                <div class="newletter-content" style="margin-bottom: 30px;">
                    '. $content .'
                </div>
                </div>
            </div>
        ';
    }

    protected function generateDokanSelect($name, array $haystack, string $label = '', $selected = false, array $errorMessages = [])
    {
        $skeleton = '<select class="dokan-form-control" name="'.$name.'">%s</select>%s</div>';

        if (!empty($label)) {
            $skeleton = '<label for="'.$name.'" class="form-label">'.$label.':</label>' . $skeleton;
        }

        $skeleton = '<div class="dokan-form-group">' . $skeleton;

        $options = '';
        $optionSkeleton = '<option value="%s"%s>%s</option>';

        foreach ($haystack as $value => $label) {
            $options .= sprintf($optionSkeleton, $value, $selected && $value == $selected ? ' selected' : '', $label);
        }

        echo sprintf($skeleton, $options, $this->inputError($name, $errorMessages));
    }

    protected function generateDokanMultiSelect($name, array $haystack, string $label = '', $selectedHaystack = false, array $errorMessages = [])
    {
        if ($selectedHaystack && (!is_array($selectedHaystack) || !count($selectedHaystack))) {
            throw new \Exception('Invalid data in selected haystack, only array allowed!');
        }

        $skeleton = '<select class="dokan-form-control" style="height: 100px !important;" name="'.$name.'[]" multiple>%s</select>%s</div>';

        if (!empty($label)) {
            $skeleton = '<label for="'.$name.'" class="form-label">'.$label.':</label>' . $skeleton;
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

    protected function getDokanIntegerInput(string $label, string $name, $value = '', $placeholder = '', array $errorMessages = [])
    {
        echo '
           <div class="dokan-form-group">
                <label for="'.$name.'" class="form-label">' . $label . ':</label>
                <input type="number" class="dokan-form-control dokan-product" name="'.$name.'" placeholder="' .$placeholder. '" id="input_'.$name.'" value="' . $value . '">
                ' . $this->inputError($name, $errorMessages) . '
           </div> 
        ';
    }

    protected function getDokanDateInput(string $label, string $name, $value = '', string $placeholder = '', array $errorMessages = [])
    {
        echo '
           <div class="dokan-form-group">
                <label for="'.$name.'" class="form-label">' . $label . ':</label>
                <input type="date" class="dokan-form-control" name="'.$name.'" placeholder="' .$placeholder. '" id="input_'.$name.'" value="' . $value . '">
                ' . $this->inputError($name, $errorMessages) . '
           </div> 
        ';
    }

    protected function inputError($name, $errors)
    {
        if (array_key_exists($name, $errors)) {
            return '<span style="margin-top: 3px; font-size: 11px; color: red;">*'.$errors[$name].'</span>';
        }

        return '';
    }
}