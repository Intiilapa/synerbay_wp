<?php


namespace SynerBay\Emails\Service;


class InviteEmail extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'inviteMail';
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