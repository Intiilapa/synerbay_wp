<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class RecommendedProducts extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/RecommendedProducts';
    }

    protected function getSubject(): string
    {
        return 'Recommended products';
    }

    protected function getEmailHead()
    {
        return 'Recommended products';
    }
}
