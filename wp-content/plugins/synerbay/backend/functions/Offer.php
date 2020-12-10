<?php
namespace SynerBay\Functions;

use SynerBay\Traits\Loader;
use SynerBay\Traits\WPAction;
use SynerBay\Module\Offer as OfferModule;

class Offer
{
    use WPAction, Loader;

    private OfferModule $offerModule;

    public function __construct()
    {
        $this->offerModule = $this->getModule('offer');

        $this->addAction('init_global_offer_by_id', 'initGlobalOffer');
    }

    public function initGlobalOffer(int $offerID)
    {
        global $offer;
        $offer = $this->offerModule->getOfferData($offerID);
    }

}