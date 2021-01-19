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
        return 'SynerBay - offer closed';
    }

    protected function getEmailHead()
    {
        return 'Offer closed';
    }
}