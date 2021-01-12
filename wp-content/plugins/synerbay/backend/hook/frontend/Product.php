<?php

namespace SynerBay\Hook\Frontend;

use SynerBay\Repository\OfferRepository;
use SynerBay\Repository\RFQRepository;
use SynerBay\Resource\Offer\DefaultOfferResource;
use SynerBay\Resource\RFQ\FullRFQResource;
use SynerBay\Traits\WPAction;

class Product
{
    use WPAction;

    public function __construct()
    {
        $this->addAction('product_buttons', 'initProductButtons');
        $this->addAction('product_rfqs', 'initRFQForProduct');
    }

    public function initProductButtons()
    {
        global $product, $rfqs;

        if ($product->get_id()) {
            $offer = (new DefaultOfferResource)->toArray((new OfferRepository())->getActiveOfferForProduct($product->get_id()));

            if (count($offer)) {
                do_action('synerbay_gotoOfferButton', $offer['url']);
            }

            do_action('synerbay_productInviteButton', $product->get_id());

            $post = get_post($product->get_id());
            if ($post && $post->post_author != get_current_user_id()) {
                $createButton = true;
                if (get_current_user_id() && count($rfqs)) {
                    foreach ($rfqs as $rfq) {
                        if ($rfq['user_id'] == get_current_user_id()) {
                            do_action('synerbay_deleteRFQButton', $rfq['id']);
                            $createButton = false;
                            break;
                        }
                    }
                }

                if ($createButton) {
                    do_action('synerbay_createRFQButton', $product->get_id());
                }
            }
        }
    }

    public function initRFQForProduct($productID)
    {
        global $rfqs;

        $rfqs = (new FullRFQResource())->collection((new RFQRepository())->getActiveRFQSForProduct((int)$productID));
    }
}