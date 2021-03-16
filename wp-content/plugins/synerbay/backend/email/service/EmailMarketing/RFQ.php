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
        return 'SynerBay - ';
    }

    protected function getEmailHead()
    {
        return '';
    }
}