<?php


namespace SynerBay\Resource\OfferApply;


use SynerBay\Repository\OfferRepository;
use SynerBay\Resource\Offer\FullOfferResource;

class FullOfferApplyResource extends OfferApplyResourceWithCustomer
{
    protected function prepare($row): array
    {
        $row = parent::prepare($row);
        $row['offer'] = (new FullOfferResource())->toArray((new OfferRepository())->getRowByPrimaryKey($row['offer_id']));
        return $row;
    }
}