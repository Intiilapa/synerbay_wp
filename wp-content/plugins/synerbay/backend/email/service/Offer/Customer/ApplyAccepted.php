<?php

namespace SynerBay\Emails\Service\Offer\Customer;

use SynerBay\Emails\Service\AbstractEmail;

class ApplyAccepted extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/customerApplyAccepted';
    }

    protected function getSubject(): string
    {
        return 'Approval';
    }

    protected function getEmailHead()
    {
        return 'Your request has been accepted.';
    }
}