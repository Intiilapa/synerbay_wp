<?php

namespace SynerBay\Emails\Service;

class InviteProductEmail extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'inviteProductMail';
    }

    protected function getSubject(): string
    {
        return 'SynerBay - invite';
    }

    protected function getEmailHead()
    {
        return 'Invite';
    }
}