<?php


namespace SynerBay\Emails\Service\Offer\Customer;


use SynerBay\Emails\Service\AbstractEmail;

class OfferEnded extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/customerOfferEnded';
    }

    protected function getSubject(): string
    {
        return 'Offer ended';
    }

    protected function getEmailHead()
    {
        return 'Offer ended';
    }
}