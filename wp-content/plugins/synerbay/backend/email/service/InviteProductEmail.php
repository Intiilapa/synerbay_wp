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
        return 'Request For Quotation';
    }

    protected function getEmailHead()
    {
        return 'You received a RFQ';
    }
}