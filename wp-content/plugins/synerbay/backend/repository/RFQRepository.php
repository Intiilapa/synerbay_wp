<?php


namespace SynerBay\Repository;


class RFQRepository extends AbstractRepository
{
    public function getActiveRFQSForProduct(int $productID)
    {
        return $this->search(['product_id' => $productID]);
    }

    protected function prepareQuery(array $searchAttributes = [])
    {
        if (isset($searchAttributes['product_id'])) {
            $product = $searchAttributes['product_id'];

            if (!is_array($product)) {
                $product = [$product];
            }

            $this->addWhereParameter($this->getBaseTable() . '.product_id in (%s)', implode(', ', $product));
        }
    }

    public function getRFQById(int $rfqID)
    {
        global $wpdb;

        return $wpdb->get_row('
            SELECT * FROM ' . $this->getBaseTable() . ' 
            where 
            id = ' . $rfqID,
            ARRAY_A);
    }

    public function deleteProductRFQ(int $productID)
    {
        global $wpdb;

        return $wpdb->delete($wpdb->prefix . 'rfq', [
            'product_id' => $productID,
        ]);
    }

    protected function getBaseTableName()
    {
        return 'rfq';
    }
}