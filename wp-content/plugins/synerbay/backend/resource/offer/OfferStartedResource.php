<?php


namespace SynerBay\Resource\Offer;


use SynerBay\Module\Product;

class OfferStartedResource extends DefaultOfferResource
{
    protected Product $productModule;

    public function __construct()
    {
        $this->productModule = new Product();
    }

    protected function prepare($row): array
    {
        $key = 'OfferStartedResource_' . $row['id'];

        if (!($data = $this->getCacheData('offer_resource', $key))) {
            $data = parent::prepare($row);
            $data['product'] = $this->productModule->getProductData($data['product_id'], true, true);
            $this->setCacheData('offer_resource', $key, $data, 60 * 60);
        }

        return $data;
    }
}