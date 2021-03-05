<?php


namespace SynerBay\Resource\RFQ;


use SynerBay\Resource\AbstractResource;

class DefaultRFQResource extends AbstractResource
{
    protected function prepare($row): array
    {
        return $row;
    }
}