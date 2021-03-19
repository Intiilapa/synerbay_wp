<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class SynerBaySocialIcon extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/SynerBaySocialIcon';
    }

    protected function getSubject(): string
    {
        return 'Something is missing from your email!';
    }

    protected function getEmailHead()
    {
        return 'Something is missing from your email!';
    }
}