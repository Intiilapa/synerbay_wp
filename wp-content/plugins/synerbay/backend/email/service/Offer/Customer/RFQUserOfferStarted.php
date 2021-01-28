<?php


namespace SynerBay\Emails\Service\Offer\Customer;


use SynerBay\Emails\Service\AbstractEmail;

class RFQUserOfferStarted extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/rfqUserOfferStarted';
    }

    protected function getSubject(): string
    {
        return 'Offer started';
    }

    protected function getEmailHead()
    {
        return 'Offer started';
    }
}