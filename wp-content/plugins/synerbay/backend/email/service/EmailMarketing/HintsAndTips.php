<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class HintsAndTips extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/HintsAndTips';
    }

    protected function getSubject(): string
    {
        return 'Hints and Tips to grow your business on SynerBay!';
    }

    protected function getEmailHead(): string
    {
        return 'Hints and Tips to grow your business on SynerBay!';
    }
}