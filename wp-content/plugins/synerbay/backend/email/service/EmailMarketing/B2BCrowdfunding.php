<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class B2BCrowdfunding extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/B2BCrowdfunding';
    }

    protected function getSubject(): string
    {
        return 'Are you interested in B2B crowdfunding? ';
    }

    protected function getEmailHead(): string
    {
        return 'Are you interested in B2B crowdfunding? ';
    }
}