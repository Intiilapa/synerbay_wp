<?php


namespace SynerBay\Rest;

use SynerBay\Module\OfferApply;

class OfferAppear extends AbstractRest
{
    /** @var OfferApply */
    private OfferApply $offerApplyModule;

    public function __construct()
    {
        $this->offerApplyModule = new OfferApply();

        $this->addRestRoute('appearOffer', 'appear_offer');
        $this->addRestRoute('disAppearOffer', 'disappear_offer');
    }
    /**
     * Appear offer
     */
    public function appearOffer()
    {
        $this->checkLogin();

        $post_data = wp_unslash($_POST);

        $offerID = isset($post_data['offerID']) ? sanitize_text_field($post_data['offerID']) : null;
        $productQty = isset($post_data['productQty']) ? sanitize_text_field($post_data['productQty']) : null;

        $this->responseSuccess($this->offerApplyModule->appearOfferForUser($this->getCurrentUserID(), $offerID,
            $productQty));
    }

    /**
     * Appear offer
     */
    public function disAppearOffer()
    {
        $this->checkLogin();

        $post_data = wp_unslash($_POST);

        $offerID = isset($post_data['offerID']) ? sanitize_text_field($post_data['offerID']) : null;

        $this->responseSuccess($this->offerApplyModule->disAppearOfferForUser($this->getCurrentUserID(), $offerID));
    }
}