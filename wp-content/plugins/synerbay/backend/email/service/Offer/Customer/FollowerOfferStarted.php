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
        return 'New offer added';
    }

    protected function getEmailHead()
    {
        return 'New offer has been launched';
    }
}