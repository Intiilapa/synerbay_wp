<?php


namespace SynerBay\HTMLElement;


class ButtonElement extends AbstractElement
{
    protected function init()
    {
        $this->addAction('offerApplyButton');
        $this->addAction('rfqButton');
        $this->addAction('productInviteButton');
        $this->addAction('gotoOfferButton');
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

    public function rfqButton()
    {
        echo 'rfq';
    }

    public function productInviteButton()
    {
        echo 'invite';
    }

    public function gotoOfferButton($url)
    {
        echo '<a href="'.$url.'" target="_blank">View Offer</a>';
    }
}