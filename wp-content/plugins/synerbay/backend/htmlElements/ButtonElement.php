<?php


namespace SynerBay\HTMLElement;


use SynerBay\Helper\RouteHelper;

class ButtonElement extends AbstractElement
{
    public function offerApplyButton($offer)
    {
        // amennyiben a sajátja az offer, akkor nem tud jelentkezni
        if ($offer['summary']['my_offer'] || !$offer['summary']['is_active']) {
            echo '';
        } else {
            if (!get_current_user_id() || !$offer['summary']['current_user_have_apply']) {
                echo "<a style='background-color: green !important;' onclick='synerbay.appearOffer(" . $offer['id'] . ")' class='button'>Place order need</a>";
            } else {
                echo "<a onclick='synerbay.disAppearOffer(" . $offer['id'] . ")' class='button'>Delete order need (" . $offer['summary']['current_user_apply_qty'] . " pc)</a>";
            }
        }
    }

    public function createRFQButton($productID)
    {
        echo '<a class="button rfq" style="background-color: green !important;" onclick="synerbay.createRFQ(' . $productID . ')">Create RFQ</a>';
    }

    public function deleteRFQButton($rfqID)
    {
        echo '<a class="button" onclick="synerbay.deleteRFQ(' . $rfqID . ')">Delete RFQ</a>';
    }

    public function synerBayInviteButton()
    {
        $url = RouteHelper::addInviteCodeToUrl(get_site_url());
        echo '<a id="invite-header-btn" class="invite-header" onclick=\'synerbay.inviteUserHeader("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'>Invite</a>';
    }

    public function synerBayInviteButtonSearch()
    {
        $url = RouteHelper::addInviteCodeToUrl(get_site_url());
        echo 'No results found <a class="searchbtn" onclick=\'synerbay.inviteUserHeader("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'>invite</a> relevant  vendors to find offers with this keyword';
    }

    public function productInviteButton($productID)
    {
        $url = RouteHelper::addInviteCodeToUrl(get_permalink($productID));
        echo '<a class="button invite" onclick=\'synerbay.inviteUserProductPage("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'>Invite</a>';
    }

    public function offerInviteButton($offerUrl)
    {
        $url = RouteHelper::addInviteCodeToUrl($offerUrl);
        echo '<a class="button invite" onclick=\'synerbay.inviteUserOfferPage("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'>Invite</a>';
    }

    public function gotoOfferButton($url)
    {
        echo '<a class="button view-offer" href="' . $url . '">View Offer</a>';
    }

    public function gotoCreateOfferButton($productID)
    {
        $url = get_site_url() . '/dashboard/new-offer?product-id='.$productID;
        echo '<a class="button view-offer" style="background-color: green !important;" href="' . $url . '">Create offer</a>';
    }

    public function headerCreateOfferButton()
    {
        $url = get_site_url() . '/dashboard/new-offer';
        echo '<a id="create-header-btn" class="invite-header" href="'.$url.'">Create offer</a>';
    }

    protected function init()
    {
        $this->addAction('offerApplyButton');
        $this->addAction('createRFQButton');
        $this->addAction('deleteRFQButton');
        $this->addAction('synerBayInviteButton');
        $this->addAction('synerBayInviteButtonSearch');
        $this->addAction('productInviteButton');
        $this->addAction('offerInviteButton');
        $this->addAction('gotoOfferButton');
        $this->addAction('gotoCreateOfferButton');
        $this->addAction('headerCreateOfferButton');
    }
}