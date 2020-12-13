<?php

namespace SynerBay\HTMLElement;

use SynerBay\Traits\WPAction;

class AbstractElement
{
    use WPAction;

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

    protected function generateSelect($name, array $haystack, string $label = '', $selected = false)
    {
        $skeleton = '<select class="dokan-form-control" name="'.$name.'">%s</select>';

        if (!empty($label)) {
            $skeleton = '<label for="'.$name.'">'.$label.':</label>' . $skeleton;
        }

        $options = '';
        $optionSkeleton = '<option value="%s"%s>%s</option>';

        foreach ($haystack as $value => $label) {
            $options .= sprintf($optionSkeleton, $value, $selected && $value == $selected ? ' selected' : '', $label);
        }

        echo sprintf($skeleton, $options);
    }

    protected function generateMultiSelect($name, array $haystack, string $label = '', $selectedHaystack = false)
    {
        if ($selectedHaystack && (!is_array($selectedHaystack) || !count($selectedHaystack))) {
            throw new \Exception('Invalid data in selected haystack, only array allowed!');
        }

        $skeleton = '<select class="dokan-form-control" name="'.$name.'" multiple>%s</select>';

        if (!empty($label)) {
            $skeleton = '<label for="'.$name.'">'.$label.':</label>' . $skeleton;
        }

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

        echo sprintf($skeleton, $options);
    }
}