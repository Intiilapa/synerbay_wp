<?php


namespace SynerBay\Emails\Product;


use SynerBay\Emails\Service\AbstractEmail;

class CreateRFQVendor extends AbstractEmail
{
    protected function getMessageParams(): array
    {
        return [
            'url' => get_permalink($this->params['product_id']),
        ];
    }

    protected function getTemplateName(): string
    {
        return 'product/vendorRFQCreated';
    }

    protected function getSubject(): string
    {
        return 'RFQ';
    }

    protected function getEmailHead()
    {
        return 'RFQ created';
    }
}