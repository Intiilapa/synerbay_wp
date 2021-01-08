<?php


namespace SynerBay\Repository;


class ProductRepository extends AbstractRepository
{
    protected function prepareQuery(array $searchAttributes = []): void
    {
        $this->addWhereParameter($this->getBaseTable() . '.post_type = %s', 'product');
    }

    protected function getBaseTableName(): string
    {
        return 'posts';
    }

    public function hasOffer(int $productId)
    {
        global $wpdb;

        return count($wpdb->get_results('SELECT id FROM ' . $wpdb->prefix. 'offers where product_id = ' . $productId . ' limit 1', ARRAY_A));
    }
}