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

        return $wpdb->get_row('SELECT id FROM ' . $wpdb->prefix. 'offers where product_id = ' . $productId . ' limit 1', ARRAY_A);
    }
}