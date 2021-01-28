<?php

namespace SynerBay\Emails\Service\Offer\Vendor;

use SynerBay\Emails\Service\AbstractEmail;
use SynerBay\Helper\RouteHelper;

class ApplyCreated extends AbstractEmail
{
    protected function getMessageParams(): array
    {
        return [
            'dashboardUrl' => RouteHelper::generateRoute('show_offer', ['id' => $this->params['id']]),
        ];
    }

    protected function getTemplateName(): string
    {
        return 'offer/vendorApplyCreated';
    }

    protected function getSubject(): string
    {
        return 'Order request';
    }

    protected function getEmailHead()
    {
        return 'You received an order request';
    }
}