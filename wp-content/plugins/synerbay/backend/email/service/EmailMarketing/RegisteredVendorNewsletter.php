<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class RegisteredVendorNewsletter extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/RegisteredVendorNewsletter';
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