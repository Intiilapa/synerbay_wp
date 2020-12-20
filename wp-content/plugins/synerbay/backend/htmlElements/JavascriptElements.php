<?php


namespace SynerBay\HTMLElement;


class JavascriptElements extends AbstractElement
{
    protected function init()
    {
        $this->addAction('getPriceStepDokanJS');
    }

    public function getPriceStepDokanJS()
    {
        echo "
            <script>
                var addNewButton = jQuery('#add-new-empty-row');
            
                // init steps for hidden input
                initPriceStepsForPost();
            
                // sor hozzáadása
                addNewButton.on('click', function (e) {
                    e.preventDefault();
            
                    jQuery('<div id=\"price_step_row\"></div>').insertBefore(jQuery(e.target))
                    .append(jQuery('#price_step_row_wrapper').html());
                });
            
                // sor törlése
                function deleteStepRow(element)
                {
                    jQuery(element).parent('div').remove();
                    initPriceStepsForPost();
                }
            
                // reinit prices
                function initPriceStepsForPost()
                {
                    var steps = {};
                    var i = 0;
                    jQuery('input[name=\"price_step_qty\"]').each(function(){
                        if(jQuery(this).closest('div').attr('id') !== 'price_step_row_wrapper') {
                            var element = {qty: jQuery(this).val()};
                            steps[i] = element;
                            i++;
                        }
            
                    });
            
                    var i = 0;
                    jQuery('input[name=\"price_step_price\"]').each(function(){
                        if(jQuery(this).closest('div').attr('id') !== 'price_step_row_wrapper') {
                            var element = {price: jQuery(this).val()};
                            steps[i] = { ...steps[i], ...element};
                            i++;
                        }
            
                    });
            
                    document.getElementById('priceSteps').value = JSON.stringify(steps);
                }
            
            </script>
        ";
    }
}