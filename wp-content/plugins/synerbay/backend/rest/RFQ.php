<?php

namespace SynerBay\Rest;

use SynerBay\Forms\Validators\Integer;
use SynerBay\Module\RFQ as RFQModule;

class RFQ extends AbstractRest
{
    private RFQModule $rfqModule;

    public function __construct()
    {
        $this->rfqModule = new RFQModule();

        $this->addRestRoute('requestForQuotation', 'request_for_quotation');

        $this->addRestRoute('deleteRequestForQuotation', 'delete_request_for_quotation');
    }

    public function requestForQuotation()
    {
        $this->checkLogin();

        $post_data = wp_unslash($_POST);

        $message = [];
        $successfulOperation = false;

        $productID = isset($post_data['productID']) ? sanitize_text_field($post_data['productID']) : null;
        $qty = isset($post_data['qty']) ? sanitize_text_field($post_data['qty']) : null;

        if (empty($qty)) {
            $message = ['error' => 'Quantity input is required! Please try again!'];
        }

        if (!(new Integer())->validate($qty)) {
            $message = ['error' => 'Invalid quantity value! PLease try again!'];
        }

        if (!count($message)) {
            $successfulOperation = $this->rfqModule->create($this->getCurrentUserID(), (int)$productID, (int)$qty);

            $message = $successfulOperation ? ['success' => 'Thank you for your RFQ!'] : ['error' => 'Something went wrong! Please try again!'];
        }

        $responseData = [
            'success'  => $successfulOperation,
            'messages' => [$message],
        ];

        $this->responseSuccess($responseData);
    }

    public function deleteRequestForQuotation()
    {
        $this->checkLogin();

        $post_data = wp_unslash($_POST);

        $rfqID = isset($post_data['rfqID']) ? sanitize_text_field($post_data['rfqID']) : null;

        $successfulOperation = $this->rfqModule->delete($rfqID, $this->getCurrentUserID());

        $message = $successfulOperation ? ['success' => 'Successfully deleted!'] : ['error' => 'Something went wrong! Please try again!'];

        $responseData = [
            'success'  => $successfulOperation,
            'messages' => [$message],
        ];

        $this->responseSuccess($responseData);
    }
}