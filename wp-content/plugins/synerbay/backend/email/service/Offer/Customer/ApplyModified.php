<?php

namespace SynerBay\Emails\Service\Offer\Customer;

use SynerBay\Emails\Service\AbstractEmail;
use SynerBay\Helper\RouteHelper;

class ApplyModified extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'offer/customerApplyModified';
    }

    protected function getSubject(): string
    {
        return 'Módosult ajánlat';
    }

    protected function getEmailHead()
    {
        return 'Az ajánlatra egy másik felhasználó is jelentkezett';
    }
}