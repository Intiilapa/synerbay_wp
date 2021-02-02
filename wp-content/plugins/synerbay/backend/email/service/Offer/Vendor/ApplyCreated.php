<?php

namespace SynerBay\Emails\Service\Offer\Vendor;

use SynerBay\Emails\Service\AbstractEmail;
use SynerBay\Helper\RouteHelper;
use WeDevs\Dokan\Vendor\Vendor;

class ApplyCreated extends AbstractEmail
{
    protected function getMessageParams(): array
    {
        /** @var Vendor $currentUser */
        $currentUser = dokan_get_vendor();

        return [
            'dashboardUrl' => RouteHelper::generateRoute('show_offer', ['id' => $this->params['id']]),
            'customerName' => $currentUser->get_name(),
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