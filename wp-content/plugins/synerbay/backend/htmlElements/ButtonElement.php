<?php


namespace SynerBay\HTMLElement;


use SynerBay\Helper\RouteHelper;
use SynerBay\Model\Offer;

class ButtonElement extends AbstractElement
{
    public function offerApplyButton($offer)
    {
        // amennyiben a sajátja az offer, akkor nem tud jelentkezni
        if ($offer['summary']['my_offer'] || !$offer['summary']['is_active'] || $offer['status'] == Offer::STATUS_CLOSED ) {
            echo '';
        } else {
            if (!get_current_user_id() || !$offer['summary']['current_user_have_apply']){
                echo "<a id='synerbay-btn' class='place-order-btn' onclick='synerbay.appearOffer(" . $offer['id'] . ")' class='button'><span>Place order need</span></a>";
            } else {
                echo "<a onclick='synerbay.disAppearOffer(" . $offer['id'] . ")' class='button'>Delete order need (" . $offer['summary']['current_user_apply_qty'] . " pc)</a>";
            }
        }
    }

    public function createRFQButton($productID)
    {
        echo '<a id="synerbay-btn" class="create-rfq-btn" onclick="synerbay.createRFQ(' . $productID . ')"><span>Create RFQ</span></a>';
    }

    public function deleteRFQButton($rfqID)
    {
        echo '<a id="synerbay-btn" class="delete-btn" onclick="synerbay.deleteRFQ(' . $rfqID . ')">Delete RFQ</a>';
    }

    public function synerBayInviteButton()
    {
        $url = RouteHelper::addInviteCodeToUrl(get_site_url());
        echo '<a id="invite-header-btn" class="invite-header" onclick=\'synerbay.inviteUserHeader("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'><span>Invite</span></a>';
    }

    public function synerBayInviteShortcodeList()
    {
        $url = RouteHelper::addInviteCodeToUrl(get_site_url());
        echo '<a id="invite-list" class="button offer_list_invite" onclick=\'synerbay.inviteUserHeader("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'><span>Invite</span></a>';
    }

    public function synerBayInviteButtonSearch()
    {
        $url = RouteHelper::addInviteCodeToUrl(get_site_url());
        echo 'No results found <a class="searchbtn" onclick=\'synerbay.inviteUserHeader("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'>invite</a> relevant  vendors to find offers with this keyword';
    }

    public function synerBayInviteButtonSearchNetwork()
    {
        $url = RouteHelper::addInviteCodeToUrl(get_site_url());
        echo 'No results found <a class="searchbtn" onclick=\'synerbay.inviteUserHeader("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'>invite</a> partners to see more listing';
    }

    public function synerBayInviteRfq()
    {
        $url = RouteHelper::addInviteCodeToUrl(get_site_url());
        echo 'No rfq enquiries found please <a class="searchbtn" onclick=\'synerbay.inviteUserHeader("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'>invite</a> relevant  vendors.';
    }

    public function productInviteButton($productID)
    {
        $url = RouteHelper::addInviteCodeToUrl(get_permalink($productID));
        echo '<a id="synerbay-btn" class="invite-btn" onclick=\'synerbay.inviteUserProductPage("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'><span>Invite</span></a>';
    }

    public function offerInviteButton($offerUrl)
    {
        $url = RouteHelper::addInviteCodeToUrl($offerUrl);
        echo '<a id="synerbay-btn" class="invite-btn" onclick=\'synerbay.inviteUserOfferPage("' . $url . '", '.(get_current_user_id() ? 'false' : 'true').')\'><span>Invite</span></a>';
    }

    public function gotoOfferButton($url)
    {
        echo '<a id="synerbay-btn" class="view-offer-btn" href="' . $url . '"><span>View Offer</span></a>';
    }

    public function gotoCreateOfferButton($productID)
    {
        $url = get_site_url() . '/dashboard/new-offer?product-id='.$productID;
        echo '<a id="synerbay-btn" class="view-offer-btn" href="' . $url . '"><span>Create offer</span></a>';
    }

    public function headerCreateOfferButton()
    {
        $url = get_site_url() . '/dashboard/new-offer';
        echo '<a id="create-header-btn" class="invite-header" href="'.$url.'"><span>Create offer</span></a>';
    }

    protected function init()
    {
        $this->addAction('offerApplyButton');
        $this->addAction('createRFQButton');
        $this->addAction('deleteRFQButton');
        $this->addAction('synerBayInviteButton');
        $this->addAction('synerBayInviteShortcodeList');
        $this->addAction('synerBayInviteButtonSearch');
        $this->addAction('synerBayInviteButtonSearchNetwork');
        $this->addAction('synerBayInviteRfq');
        $this->addAction('productInviteButton');
        $this->addAction('offerInviteButton');
        $this->addAction('gotoOfferButton');
        $this->addAction('gotoCreateOfferButton');
        $this->addAction('headerCreateOfferButton');
    }
}