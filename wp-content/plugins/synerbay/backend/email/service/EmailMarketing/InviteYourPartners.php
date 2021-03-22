<?php

namespace SynerBay\Emails\Service\EmailMarketing;

use SynerBay\Emails\Service\AbstractEmail;

class InviteYourPartners extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'emailMarketing/InviteYourPartners';
    }

    protected function getSubject(): string
    {
        return 'Your partners are waiting for your invitation.';
    }

    protected function getEmailHead()
    {
        return 'Your partners are waiting for your invitation.';
    }
}
