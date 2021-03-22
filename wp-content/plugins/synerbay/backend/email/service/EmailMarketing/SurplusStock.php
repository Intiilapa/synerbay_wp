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
        return 'Need some help to sell surplus stock?';
    }

    protected function getEmailHead(): string
    {
        return 'Need some help to sell surplus stock?';
    }
}
