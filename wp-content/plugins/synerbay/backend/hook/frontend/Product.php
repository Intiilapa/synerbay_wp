<?php

namespace SynerBay\Hook\Frontend;

use SynerBay\Repository\OfferRepository;
use SynerBay\Resource\Offer\DefaultOfferResource;
use SynerBay\Traits\WPAction;

class Product
{
    use WPAction;

    public function __construct()
    {
        $this->addAction('product_buttons', 'initProductButtons');
    }

    public function initProductButtons()
    {
        global $product;

        if ($product->get_id()) {
            $offer = (new DefaultOfferResource)->toArray((new OfferRepository())->getActiveOfferForProduct($product->get_id()));

            if (count($offer)) {
                do_action('synerbay_gotoOfferButton', $offer['url']);
            }

            do_action('synerbay_productInviteButton', $product->get_id());

            // todo rfq button
        }
    }
}