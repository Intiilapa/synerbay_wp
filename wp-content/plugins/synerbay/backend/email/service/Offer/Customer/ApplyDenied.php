<?php

namespace SynerBay\Emails\Service\Offer\Customer;

use SynerBay\Emails\Service\AbstractEmail;

class ApplyDenied extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/customerApplyDenied';
    }

    protected function getSubject(): string
    {
        return 'Request rejected';
    }

    protected function getEmailHead()
    {
        return 'Your request has been rejected.';
    }
}