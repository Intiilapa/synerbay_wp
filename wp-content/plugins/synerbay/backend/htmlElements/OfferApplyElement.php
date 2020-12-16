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

    public function offerApplyButton(WC_Product $product)
    {
        // TODO Remco a wp-ben a product azaz offer?
        // amennyiben a sajátja az offer, akkor nem tud jelentkezni
        if (current_user_can('edit_post', $product->get_id())) {
            echo '';
        }

        // jelentekezett máár rá vagy nem?
        // todo Remco színezd meg légyszi a gombokat és a js függvényx bemenő paraméterének kellene az offer id
        if (!get_current_user_id() || (get_current_user_id() && !$this->offerApplyModule->isUserAppliedOffer(get_current_user_id(), $product->get_id()))) {
            echo  "<button type='button' style='background-color: green !important;' onclick='window.synerbay.appearOffer(".$product->get_id().")' class='button'>[angol szöveg] jelentkezés</button>";
        } else {
            echo  "<button type='button' onclick='window.synerbay.disAppearOffer(".$product->get_id().")' class='button'>[angol szöveg] lejelentkezés</button>";
        }
    }

    public function disAppearOfferDashBoardButton($offerID)
    {
        echo  "<a onclick='window.synerbay.disAppearOffer(".$offerID.")' class='button'>x</a>";
    }
}