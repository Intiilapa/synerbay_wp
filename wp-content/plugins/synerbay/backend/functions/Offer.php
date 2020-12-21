<?php
namespace SynerBay\Functions;

use SynerBay\Forms\CreateOffer;
use SynerBay\Forms\UpdateOffer;
use SynerBay\Traits\Loader;
use SynerBay\Traits\Toaster;
use SynerBay\Traits\WPAction;

class Offer
{
    use WPAction, Loader, Toaster;

    public function __construct()
    {
        $this->addAction('init_global_offer_by_id', 'initGlobalOffer');
        $this->addAction('init_global_my_offers_for_dashboard', 'initGlobalMyOffers');
        $this->addAction('init_global_my_offer_applies_for_dashboard', 'initGlobalMyOfferApplies');
        $this->addAction('get_offer_create_form', 'getOfferCreateForm');
        $this->addAction('create_offer', 'createOffer');
        $this->addAction('get_offer_update_form', 'getOfferUpdateForm');
        $this->addAction('update_offer', 'updateOffer');
    }

    public function initGlobalOffer(int $offerID)
    {
        global $offer;
        $offer = $this->getModule('offer')->getOfferData($offerID, true, true, true, true);
    }

    public function initGlobalMyOffers()
    {
        global $myOffers;
        $myOffers = $this->getModule('offer')->getMyOffersForDashboard();
    }

    public function initGlobalMyOfferApplies()
    {
        global $myOfferApplies;
        $myOfferApplies = $this->getModule('offerApply')->getMyOfferAppliesForDashboard();
    }

    public function getOfferCreateForm($postData)
    {
        return new CreateOffer($postData);
    }

    public function createOffer($filteredData)
    {
        return $this->getModule('offer')->createOffer($filteredData);
    }

    public function getOfferUpdateForm($postData)
    {
        return new UpdateOffer($postData);
    }

    public function updateOffer($filteredData)
    {
        return $this->getModule('offer')->updateOffer($filteredData);
    }
}