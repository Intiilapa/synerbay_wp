<?php


namespace SynerBay\Rest;

use Stripe\ApiOperations\Request;
use SynerBay\Module\OfferApply;
use SynerBay\Traits\Loader;

class OfferAppear extends AbstractRest
{
    use Loader;

    public function __construct()
    {
        $this->addRestRoute('appearOffer', 'appear_offer');
        $this->addRestRoute('disAppearOffer', 'disappear_offer');
        $this->addRestRoute('acceptApply', 'accept_apply');
        $this->addRestRoute('rejectApply', 'reject_apply');
    }

    /**
     * Appear offer
     */
    public function appearOffer()
    {
        $this->checkLogin();

        $post_data = wp_unslash($_POST);
        /** @var OfferApply $module */
        $module = $this->getModule('offerApply');

        $offerID = isset($post_data['offerID']) ? sanitize_text_field($post_data['offerID']) : null;
        $productQty = isset($post_data['productQty']) ? sanitize_text_field($post_data['productQty']) : null;

        $success = $module->createAppearOfferForUser($this->getCurrentUserID(), $offerID, $productQty);

        $message = $success ? ['success' => 'Successful operation!'] : ['error' => $module->getErrorMessage()];

        $responseData = [
            'success' => $success,
            'messages' => [$message],
        ];

        $this->responseSuccess($responseData);
    }

    /**
     * Appear offer
     */
    public function disAppearOffer()
    {
        $this->checkLogin();

        $post_data = wp_unslash($_POST);
        /** @var OfferApply $module */
        $module = $this->getModule('offerApply');

        $offerID = isset($post_data['offerID']) ? sanitize_text_field($post_data['offerID']) : null;

        $success = $module->deleteAppearOfferForUser($this->getCurrentUserID(), $offerID);

        $message = $success ? ['success' => 'Successful operation!'] : ['error' => $module->getErrorMessage()];

        $responseData = [
            'success' => $success,
            'messages' => [$message],
        ];

        $this->responseSuccess($responseData);
    }

    /**
     * vendor - ajánlat elfogadása
     */
    public function acceptApply()
    {
        $this->checkLogin();

        $post_data = wp_unslash($_POST);
        /** @var OfferApply $module */
        $module = $this->getModule('offerApply');

        $applyID = isset($post_data['id']) ? sanitize_text_field($post_data['id']) : null;

        $success = $module->accept((int)$applyID);

        $message = $success ? ['success' => 'Successful operation!'] : ['error' => 'Something went wrong, please try again!'];

        $responseData = [
            'success' => $success,
            'messages' => [$message],
        ];

        $this->responseSuccess($responseData);
    }

    /**
     * vendor - ajánlat elutasítása
     */
    public function rejectApply()
    {
        $this->checkLogin();

        $post_data = wp_unslash($_POST);

        /** @var OfferApply $module */
        $module = $this->getModule('offerApply');

        $applyID = isset($post_data['id']) ? sanitize_text_field($post_data['id']) : null;
        $reason = isset($post_data['reason']) ? sanitize_text_field($post_data['reason']) : '';

        $success = $module->reject((int)$applyID, $reason);

        $message = $success ? ['success' => 'Successful operation!'] : ['error' => 'Something went wrong, please try again!'];

        $responseData = [
            'success' => $success,
            'messages' => [$message],
        ];

        $this->responseSuccess($responseData);
    }
}