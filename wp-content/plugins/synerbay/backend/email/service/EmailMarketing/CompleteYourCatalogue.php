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
        return 'Complete your catalogue to increase sales!';
    }

    protected function getEmailHead()
    {
        return 'Complete your catalogue to increase sales!';
    }
}
