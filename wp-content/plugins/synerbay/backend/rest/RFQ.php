<?php
namespace SynerBay\Rest;

use SynerBay\Module\RFQ as RFQModule;

class RFQ extends AbstractRest
{
    private RFQModule $rfqModule;

    public function __construct()
    {
        $this->rfqModule = new RFQModule();
    }

    public function requestForQuotation()
    {
        $this->checkLogin();

        $post_data = wp_unslash($_POST);

        $productID = isset($post_data['productID']) ? sanitize_text_field($post_data['productID']) : null;

        $this->responseSuccess($this->rfqModule->appear($this->getCurrentUserID(), $productID));
    }

    public function deleteRequestForQuotation()
    {
        $this->checkLogin();

        $post_data = wp_unslash($_POST);

        $productID = isset($post_data['productID']) ? sanitize_text_field($post_data['productID']) : null;

        $this->responseSuccess($this->rfqModule->disappear($this->getCurrentUserID(), $productID));
    }
}