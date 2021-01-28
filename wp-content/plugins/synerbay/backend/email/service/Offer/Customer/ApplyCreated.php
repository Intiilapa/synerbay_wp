<?php

namespace SynerBay\Emails\Service\Offer\Customer;

use SynerBay\Emails\Service\AbstractEmail;

class ApplyCreated extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/customerApplyCreated';
    }

    protected function getSubject(): string
    {
        return 'Successful request';
    }

    protected function getEmailHead()
    {
        return 'Your request has been sent';
    }
}