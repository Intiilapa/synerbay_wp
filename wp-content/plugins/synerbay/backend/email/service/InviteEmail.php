<?php


namespace SynerBay\Emails\Service;


class InviteEmail extends AbstractEmail
{
    protected function getMessageParams(): array
    {
        return [
            'message' => 'Meghívtak az alábbi oldalra!'
        ];
    }

    protected function getTemplateName(): string
    {
        return 'inviteMail';
    }

    protected function getSubject(): string
    {
        return 'SynerBay - invite';
    }
}