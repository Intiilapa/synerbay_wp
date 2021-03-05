<?php


namespace SynerBay\Resource\OfferApply;


use SynerBay\Resource\AbstractResource;

class DefaultOfferApplyResource extends AbstractResource
{
    protected function prepare($row): array
    {
        return $row;
    }
}