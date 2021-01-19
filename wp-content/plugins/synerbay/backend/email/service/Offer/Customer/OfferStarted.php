<?php


namespace SynerBay\Emails\Service\Offer\Customer;


use SynerBay\Emails\Service\AbstractEmail;

class OfferStarted extends AbstractEmail
{
    protected function getSubject(): string
    {
        return 'Offer started';
    }
}