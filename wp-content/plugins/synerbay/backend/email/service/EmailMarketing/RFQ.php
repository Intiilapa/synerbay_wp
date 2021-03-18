<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class RFQ extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/RFQ';
    }

    protected function getSubject(): string
    {
        return 'Here\'s how you can find the best offers!';
    }

    protected function getEmailHead()
    {
        return 'Here\'s how you can find the best offers!';
    }
}