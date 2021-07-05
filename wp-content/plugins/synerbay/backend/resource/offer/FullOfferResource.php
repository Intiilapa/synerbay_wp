<?php


namespace SynerBay\Resource\Offer;


use SynerBay\Module\Offer;
use SynerBay\Module\OfferApply;
use SynerBay\Module\Product;

class FullOfferResource extends DefaultOfferResource
{
    protected Offer $offerModule;
    protected Product $productModule;
    protected OfferApply $offerApplyModule;

    public function __construct()
    {
        $this->offerModule = new Offer();
        $this->productModule = new Product();
        $this->offerApplyModule = new OfferApply();
    }

    protected function prepare($row): array
    {
        $key = 'FullOfferResource_' . $row['id'];

        if (!($data = $this->getCacheData('offer_resource', $key))) {
            $data = parent::prepare($row);
            $data['vendor'] = dokan_get_vendor($data['user_id']);
            $data['product'] = $this->productModule->getProductData($data['product_id'], true, true);
            $data['applies'] = $this->offerApplyModule->getAppliesForOffer($data['id'], true);
            $this->setCacheData('offer_resource', $key, $data, 60 * 60);
        }

        $data['summary'] = $this->offerModule->getOfferSummaryData($data);

//        print '<pre>';
//        var_dump($data['summary']);
//        die;

        return $data;
    }
}