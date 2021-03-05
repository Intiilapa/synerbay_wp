<?php


namespace SynerBay\Resource\RFQ;


use SynerBay\Module\Product;

class FullRFQResource extends VendorRFQResource
{
    protected Product $productModule;

    public function __construct()
    {
        $this->productModule = new Product();
    }

    protected function prepare($row): array
    {
        $data = parent::prepare($row);
        $data['product'] = $this->productModule->getProductData($data['product_id'], true, true);
        return $data;
    }
}