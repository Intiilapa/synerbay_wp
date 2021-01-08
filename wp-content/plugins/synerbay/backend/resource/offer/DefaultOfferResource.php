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
        $offer = $row;
        $offer['price_steps'] = StringHelper::isJson($offer['price_steps']) ? json_decode($offer['price_steps'], true) : [];
        $offer['shipping_to'] = StringHelper::isJson($offer['shipping_to']) ? json_decode($offer['shipping_to'], true) : [$offer['shipping_to']];
        $offer['shipping_to_labels'] = implode(', ', SynerBayDataHelper::setupDeliveryDestinationsForOfferData($offer['shipping_to']));
        $offer['url'] = RouteHelper::generateRoute('offer_sub_page', ['id' => $offer['id']]);
        $offer['transport_parity'] = strtoupper($offer['transport_parity']);

        return $offer;
    }
}