<?php


namespace SynerBay\Resource\OfferApply;


class OfferApplyResourceWithCustomer extends DefaultOfferApplyResource
{
    protected function prepare($row): array
    {
        $row = parent::prepare($row);
        $row['customer'] = dokan_get_vendor($row['user_id']);
        return $row;
    }
}