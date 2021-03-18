<?php


namespace SynerBay\Repository;


class ProductRepository extends AbstractRepository
{
    protected function prepareQuery(array $searchAttributes = []): void
    {
        $this->addWhereParameter($this->getBaseTable() . '.post_type = %s', 'product');

        if (!empty($searchAttributes['created_at'])) {
            $this->addWhereParameter($this->getBaseTable() . '.post_date = %s', $searchAttributes['created_at']);
        }

        if (!empty($searchAttributes['created_at_greater_equal'])) {
            $this->addWhereParameter($this->getBaseTable() . '.post_date >= %s', $searchAttributes['created_at_greater_equal']);
        }

        if (!empty($searchAttributes['is_active']) && $searchAttributes['is_active']) {
            $this->addWhereParameter($this->getBaseTable() . '.post_status = %s', 'publish');
        }
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

    public function getProductBaseDataWithMainCategory(array $searchParams = [])
    {
        global $wpdb;

        $select = "
            select
            p.ID as id,
            p.post_title as name,
            p.guid as url,
            sb_terms.term_id as main_category_id,
            sb_terms.name as main_category_name
        from
            sb_term_taxonomy
                left join sb_terms on sb_term_taxonomy.term_taxonomy_id = sb_terms.term_id
                left join sb_term_relationships str on sb_term_taxonomy.term_taxonomy_id = str.term_taxonomy_id
                left join sb_posts p on str.object_id = p.ID
        where
                sb_term_taxonomy.parent = 0 and
                sb_term_taxonomy.taxonomy = 'product_cat' and
                p.post_type = 'product'
        ";

        if (!empty($searchAttributes['is_active']) && $searchAttributes['is_active']) {
            $select .= ' and p.post_status >= "publish"';
        }

        if (!empty($searchParams['created_at'])) {
            $select .= ' and p.post_date = "'.$searchParams['created_at'].'"';
        }

        if (!empty($searchParams['created_at_greater_equal'])) {
            $select .= ' and p.post_date >= "'.$searchParams['created_at_greater_equal'].'"';
        }

        return $wpdb->get_results($select, ARRAY_A);
    }
}