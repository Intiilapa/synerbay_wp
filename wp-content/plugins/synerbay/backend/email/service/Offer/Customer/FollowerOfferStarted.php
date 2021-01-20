<?php


namespace SynerBay\Emails\Service\Offer\Customer;


use SynerBay\Emails\Service\AbstractEmail;

class FollowerOfferStarted extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/followerOfferStarted';
    }

    protected function getSubject(): string
    {
        return 'SynerBay - offer started';
    }

    protected function getEmailHead()
    {
        return 'Offer started';
    }
}