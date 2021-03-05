<?php

namespace SynerBay\Emails\Service;

class InviteOfferEmail extends AbstractEmail
{
    protected function getTemplateName(): string
    {
        return 'inviteOfferMail';
    }

    protected function getSubject(): string
    {
        return 'Invitation';
    }

    protected function getEmailHead()
    {
        return 'Invitation';
    }
}