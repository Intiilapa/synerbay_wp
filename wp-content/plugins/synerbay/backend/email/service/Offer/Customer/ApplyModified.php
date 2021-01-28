<?php

namespace SynerBay\Emails\Service\Offer\Customer;

use SynerBay\Emails\Service\AbstractEmail;

class ApplyModified extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/customerApplyModified';
    }

    protected function getSubject(): string
    {
        return 'The offer you attend has updates!';
    }

    protected function getEmailHead()
    {
        return 'New customers attend the offer.';
    }
}