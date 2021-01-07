<?php

namespace SynerBay\Emails\Service\Offer\Vendor;

use SynerBay\Emails\Service\AbstractEmail;
use SynerBay\Helper\RouteHelper;

class ApplyModified extends AbstractEmail
{
    protected function getMessageParams(): array
    {
        return [
            'dashboardUrl' => RouteHelper::generateRoute('show_offer', ['id' => $this->params['id']]),
        ];
    }

    protected function getTemplateName(): string
    {
        return 'offer/vendorApplyModified';
    }

    protected function getSubject(): string
    {
        return 'Offer apply';
    }

    protected function getEmailHead()
    {
        return 'Offer apply created';
    }
}