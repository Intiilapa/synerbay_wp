<?php

namespace SynerBay\Emails\Service\Offer\Customer;

use SynerBay\Emails\Service\AbstractEmail;
use SynerBay\Helper\RouteHelper;

class ApplyDenied extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/customerApplyDenied';
    }

    protected function getSubject(): string
    {
        return 'Jelentkezésedet elutasították';
    }

    protected function getEmailHead()
    {
        return 'Elutasított jelentkezés';
    }
}