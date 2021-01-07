<?php

namespace SynerBay\Emails\Service\Offer\Customer;

use SynerBay\Emails\Service\AbstractEmail;
use SynerBay\Helper\RouteHelper;

class ApplyCreated extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/customerApplyCreated';
    }

    protected function getSubject(): string
    {
        return 'Sikeresen jelentkezés';
    }

    protected function getEmailHead()
    {
        return 'Sikeresen jelentkezés';
    }
}