<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class WeeklyAnalytics extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/WeeklyAnalytics';
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