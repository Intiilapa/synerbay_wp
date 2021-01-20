<?php


namespace SynerBay\Emails\Service\Offer\Admin;


use SynerBay\Emails\Service\AbstractEmail;

class OfferStarted extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/adminOfferStarted';
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