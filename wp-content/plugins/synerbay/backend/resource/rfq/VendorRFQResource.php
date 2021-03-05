<?php


namespace SynerBay\Resource\RFQ;


class VendorRFQResource extends DefaultRFQResource
{
    protected function prepare($row): array
    {
        $data = parent::prepare($row);
        $data['vendor'] = dokan_get_vendor($data['user_id']);
        return $data;
    }
}