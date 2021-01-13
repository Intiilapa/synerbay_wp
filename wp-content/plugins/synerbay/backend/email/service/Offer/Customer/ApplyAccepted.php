<?php

namespace SynerBay\Emails\Service\Offer\Customer;

use SynerBay\Emails\Service\AbstractEmail;
use SynerBay\Helper\RouteHelper;

class ApplyAccepted extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/customerApplyAccepted';
    }

    protected function getSubject(): string
    {
        return 'Jóváhagyás';
    }

    protected function getEmailHead()
    {
        return 'Jelentkezésedet jóváhagyták';
    }
}