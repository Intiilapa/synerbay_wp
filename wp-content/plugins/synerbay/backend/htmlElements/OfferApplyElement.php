<?php
namespace SynerBay\HTMLElement;

use SynerBay\Module\OfferApply as OfferApplyModule;
use WC_Product;

class OfferApplyElement extends AbstractElement
{
    /** @var OfferApplyModule $offerApply */
    private OfferApplyModule $offerApplyModule;

    public function init()
    {
        $this->offerApplyModule = new OfferApplyModule();
        $this->addAction('offerApplyButton');
    }

    public function offerApplyButton($offer)
    {
        $currentDate = strtotime(date('Y-m-d H:i:s'));
        // TODO Remco a wp-ben a product azaz offer?
        // amennyiben a sajátja az offer, akkor nem tud jelentkezni
        if (current_user_can('edit_post', $offer['product']['wc_product']->get_id())) {
            echo '';
        } else if ($currentDate < strtotime($offer['offer_start_date']) || strtotime($offer['offer_start_date']) > $currentDate) {
            echo '';
        } else if (!get_current_user_id() || (get_current_user_id() && !$this->offerApplyModule->isUserAppliedOffer(get_current_user_id(), $offer['id']))) {
            echo  "<button type='button' style='background-color: green !important;' onclick='window.synerbay.appearOffer(".$offer['id'].")' class='button'>Place order need</button>";
        } else {
            echo  "<button type='button' onclick='window.synerbay.disAppearOffer(".$offer['id'].")' class='button'>Delete order need</button>";
        }
    }
}