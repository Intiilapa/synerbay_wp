<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class HowToReachCustomers extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/HowToReachCustomers';
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