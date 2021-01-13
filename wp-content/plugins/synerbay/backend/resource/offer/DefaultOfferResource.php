<?php

namespace SynerBay\Resource\Offer;

use SynerBay\Helper\RouteHelper;
use SynerBay\Helper\StringHelper;
use SynerBay\Helper\SynerBayDataHelper;
use SynerBay\Resource\AbstractResource;

class DefaultOfferResource extends AbstractResource
{
    protected function prepare($row): array
    {
        $row['price_steps'] = StringHelper::isJson($row['price_steps']) ? json_decode($row['price_steps'], true) : [];
        $row['shipping_to'] = StringHelper::isJson($row['shipping_to']) ? json_decode($row['shipping_to'], true) : [$row['shipping_to']];
        $row['shipping_to_labels'] = implode(', ', SynerBayDataHelper::setupDeliveryDestinationsForOfferData($row['shipping_to']));
        $row['url'] = RouteHelper::generateRoute('offer_sub_page', ['id' => $row['id']]);
        $row['transport_parity'] = strtoupper($row['transport_parity']);

        return $row;
    }
}