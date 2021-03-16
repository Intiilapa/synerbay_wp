<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class SurplusStock extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/SurplusStock';
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