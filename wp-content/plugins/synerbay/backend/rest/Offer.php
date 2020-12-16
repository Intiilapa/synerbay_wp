<?php


namespace SynerBay\Rest;

use SynerBay\Module\Offer as OfferModule;
use SynerBay\Traits\Loader;

class Offer extends AbstractRest
{
    use Loader;
    /** @var OfferModule */
    private OfferModule $offerModule;

    public function __construct()
    {
        $this->offerModule = $this->getModule('offer');

        $this->addRestRoute('deleteOffer', 'delete_offer');
    }

    /**
     * Delete offer
     */
    public function deleteOffer()
    {
        $this->checkLogin();

        $postData = wp_unslash($_POST);

        $offerID = isset($postData['offerID']) ? sanitize_text_field($postData['offerID']) : null;

        $responseData = [
            'deleted' => $this->offerModule->deleteOffer($this->getCurrentUserID(), $offerID),
        ];

        $this->responseSuccess($responseData);
    }
}