<?php


namespace SynerBay\Pages\Shop;


use SynerBay\Pages\AbstractPage;

class Offer extends AbstractPage
{
    protected function init()
    {
        parent::init();
        $this->addAction('init_global_offer_sub_page', 'subPage');
    }

    public function subPage(int $offerID) {
        global $offer;
        do_action('synerbay_init_global_offer_by_id', $offerID);

        if (!$offer || !count($offer)) {
            $this->page404();
        }
    }
}