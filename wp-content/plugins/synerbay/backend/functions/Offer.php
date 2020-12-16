<?php
namespace SynerBay\Functions;

use SynerBay\Traits\Loader;
use SynerBay\Traits\WPAction;
use SynerBay\Module\Offer as OfferModule;
use SynerBay\Module\OfferApply as OfferApplyModule;

class Offer
{
    use WPAction, Loader;

    private OfferModule $offerModule;
    private OfferApplyModule $offerApplyModule;

    public function __construct()
    {
        $this->offerModule = $this->getModule('offer');
        $this->offerApplyModule = $this->getModule('offerApply');

        $this->addAction('init_global_offer_by_id', 'initGlobalOffer');
        $this->addAction('init_global_my_offers_for_dashboard', 'initGlobalMyOffers');
        $this->addAction('init_global_my_offer_applies_for_dashboard', 'initGlobalMyOfferApplies');
        $this->addAction('get_offer_form', 'getOfferForm');
        $this->addAction('create_offer', 'createOffer');
    }

    public function initGlobalOffer(int $offerID)
    {
        global $offer;
        $offer = $this->offerModule->getOfferData($offerID, true, true, true, true);
    }

    public function initGlobalMyOffers()
    {
        global $myOffers;
        $myOffers = $this->offerModule->getMyOffersForDashboard();
    }

    public function initGlobalMyOfferApplies()
    {
        global $myOfferApplies;
        $myOfferApplies = $this->offerApplyModule->getMyOfferAppliesForDashboard();
    }

    public function getOfferForm($postData)
    {
        return new \SynerBay\Forms\Offer($postData);
    }

    public function createOffer($filteredData)
    {
        return $this->offerModule->createOffer($filteredData);
    }

}