<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class RecommendedOffers extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/RecommendedOffers';
    }

    protected function getSubject(): string
    {
        return 'Recommended offers';
    }

    protected function getEmailHead()
    {
        return 'Recommended offers';
    }
}
