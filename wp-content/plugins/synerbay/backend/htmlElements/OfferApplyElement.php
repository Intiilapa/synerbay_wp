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
        // amennyiben a sajátja az offer, akkor nem tud jelentkezni
        if ($offer['summary']['my_offer'] || !$offer['summary']['is_active']) {
            echo '';
        } else if (!get_current_user_id() || !$offer['summary']['current_user_have_apply']) {
            echo  "<button type='button' style='background-color: green !important;' onclick='window.synerbay.appearOffer(".$offer['id'].")' class='button'>Place order need</button>";
        } else {
            echo  "<button type='button' onclick='window.synerbay.disAppearOffer(".$offer['id'].")' class='button'>Delete order need (". $offer['summary']['current_user_apply_qty'] ." pc)</button>";
        }
    }
}