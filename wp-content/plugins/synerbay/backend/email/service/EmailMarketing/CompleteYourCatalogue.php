<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class CompleteYourCatalogue extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/CompleteYourCatalogue';
    }

    protected function getSubject(): string
    {
        return 'SynerBay - CompleteYourCatalogue';
    }

    protected function getEmailHead()
    {
        return 'CompleteYourCatalogue';
    }
}