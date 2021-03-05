<?php


namespace SynerBay\Emails\Service\Offer\Admin;


use SynerBay\Emails\Service\AbstractEmail;

class OfferEnded extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/adminOfferEnded';
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