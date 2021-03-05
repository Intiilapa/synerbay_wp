<?php


namespace SynerBay\Emails\Service\Offer\Vendor;


use SynerBay\Emails\Service\AbstractEmail;

class OfferEnded extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/vendorOfferEnded';
    }

    protected function getSubject(): string
    {
        return 'Offer closed';
    }

    protected function getEmailHead()
    {
        return 'Offer closed';
    }
}