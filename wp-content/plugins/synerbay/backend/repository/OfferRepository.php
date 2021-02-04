<?php


namespace SynerBay\Repository;


class OfferRepository extends AbstractRepository
{
    public function getActiveOfferForProduct(int $productID)
    {
        global $wpdb;

        $currentDate = date('Y-m-d H:i:s');
        $table = $wpdb->prefix . 'offers';

        return $wpdb->get_row('
            SELECT * FROM ' . $table . ' 
            where 
            product_id = ' . $productID . ' 
            and 
            offer_start_date <= "' . $currentDate . '"
            and 
            offer_end_date >= "' . $currentDate . '"
            order by id desc limit 1',
            ARRAY_A);
    }

    public function increaseQty(int $offerID, int $currentOfferQty = 0, int $increaseQty = 0)
    {
        global $wpdb;

        return $wpdb->update(
            $wpdb->prefix . 'offers',
            ['current_quantity' => $currentOfferQty + $increaseQty],
            ['id' => $offerID],
            ['%d'],
            ['%d']
        );
    }

    public function decreaseQty(int $offerID, int $currentOfferQty = 0, int $decreaseQty = 0)
    {
        global $wpdb;

        return $wpdb->update(
            $wpdb->prefix . 'offers',
            ['current_quantity' => $currentOfferQty - $decreaseQty],
            ['id' => $offerID],
            ['%d'],
            ['%d']
        );
    }

    public function changeStatus(int $offerID, string $status)
    {
        global $wpdb;

        return $wpdb->update(
            $wpdb->prefix . 'offers',
            ['status' => $status],
            ['id' => $offerID],
            ['%s'],
            ['%d']
        );
    }

    protected function prepareQuery(array $searchAttributes = [])
    {
        $addProductJoin = false;
        $addCategoryJoin = false;

        if (!empty($searchAttributes['query'])) {
            $addProductJoin = true;
            $query = $searchAttributes['query'];
            $this->addWhereParameter('sb_posts.post_title like %s', '%'.$query.'%');
        }

        if (isset($searchAttributes['recent_offers'])) {
            $currentDate = date('Y-m-d H:i:s');
            $this->addWhereParameter($this->getBaseTable() . '.offer_start_date <= %s', $currentDate);
            $this->addWhereParameter($this->getBaseTable() . '.offer_end_date >= %s', $currentDate);
        }

        if (isset($searchAttributes['my_offers'])) {
            $this->addWhereParameter($this->getBaseTable() . '.user_id = %d', get_current_user_id());
        }

        if (!empty($searchAttributes['product_id'])) {
            $product = $searchAttributes['product_id'];

            if (!is_array($product)) {
                $product = [$product];
            }

            $this->addWhereParameter($this->getBaseTable() . '.product_id in ('.$this->buildInPlaceholderFromArrayToWhere($product).')', $product);
        }

        if (!empty($searchAttributes['user_id'])) {
            $user = $searchAttributes['user_id'];

            if (!is_array($user)) {
                $user = [$user];
            }

            $this->addWhereParameter($this->getBaseTable() . '.user_id in ('.$this->buildInPlaceholderFromArrayToWhere($user).')', $user);
        }

        if (!empty($searchAttributes['transport_parity'])) {
            $value = $searchAttributes['transport_parity'];

            $this->addWhereParameter($this->getBaseTable() . '.transport_parity = %s', $value);
        }

        if (!empty($searchAttributes['shipping_to'])) {
            $value = $searchAttributes['shipping_to'];

            $this->addWhereParameter('MATCH('.$this->getBaseTable().'.shipping_to) AGAINST (%s IN NATURAL LANGUAGE MODE)', $value);
        }

        if (!empty($searchAttributes['category_id'])) {
            $value = $searchAttributes['category_id'];
            $addProductJoin = true;
            $addCategoryJoin = true;
            $this->addWhereParameter('sb_term_relationships.term_taxonomy_id = %d', $value);
        }

        if (!empty($searchAttributes['status'])) {
            $status = $searchAttributes['status'];

            if (!is_array($status)) {
                $status = [$status];
            }

            $this->addWhereParameter($this->getBaseTable() . '.status in ('.$this->buildInPlaceholderFromArrayToWhere($status).')', $status);
        }

        if (!empty($searchAttributes['visible'])) {
            $visible = $searchAttributes['visible'];
            $this->addWhereParameter($this->getBaseTable() . '.visible = %s', $visible ? 'yes' : 'no');
        }

        if (!empty($searchAttributes['default_price'])) {
            $value = $searchAttributes['default_price'];
            $this->addWhereParameter($this->getBaseTable() . '.default_price = %d', $value);
        }

        if (!empty($searchAttributes['default_price_from'])) {
            $value = $searchAttributes['default_price_from'];
            $this->addWhereParameter($this->getBaseTable() . '.default_price <= %d', $value);
        }

        if (!empty($searchAttributes['default_price_to'])) {
            $value = $searchAttributes['default_price_to'];
            $this->addWhereParameter($this->getBaseTable() . '.default_price >= %d', $value);
        }

        if (!empty($searchAttributes['except_ended'])) {
            $this->addWhereParameter($this->getBaseTable() . '.offer_end_date > %s', date('Y-m-d H:i:s'));
        }

        if (isset($searchAttributes['ended'])) {
            $this->addWhereParameter($this->getBaseTable() . '.offer_end_date <= %s', date('Y-m-d H:i:s'));
        }

        if (isset($searchAttributes['started'])) {
            $this->addWhereParameter($this->getBaseTable() . '.offer_start_date <= %s', date('Y-m-d H:i:s'));
        }

        if (!empty($searchAttributes['offer-start-date'])) {
            $this->addWhereParameter($this->getBaseTable() . '.offer_start_date = %s', $searchAttributes['offer-start-date']);
        }

        if (!empty($searchAttributes['offer-start-date-from'])) {
            $this->addWhereParameter($this->getBaseTable() . '.offer_start_date <= %s', $searchAttributes['offer-start-date-from']);
        }

        if (!empty($searchAttributes['offer-start-date-to'])) {
            $this->addWhereParameter($this->getBaseTable() . '.offer_start_date >= %s', $searchAttributes['offer-start-date-to']);
        }

        if (!empty($searchAttributes['offer-end-date'])) {
            $this->addWhereParameter($this->getBaseTable() . '.offer_end_date = %s', $searchAttributes['offer-end-date']);
        }

        if (!empty($searchAttributes['offer-end-date-from'])) {
            $this->addWhereParameter($this->getBaseTable() . '.offer_end_date <= %s', $searchAttributes['offer-end-date-from']);
        }

        if (!empty($searchAttributes['offer-end-date-to'])) {
            $this->addWhereParameter($this->getBaseTable() . '.offer_end_date >= %s', $searchAttributes['offer-end-date-to']);
        }

        if (!empty($searchAttributes['delivery-date'])) {
            $this->addWhereParameter($this->getBaseTable() . '.delivery_date = %s', $searchAttributes['delivery-date']);
        }

        if (!empty($searchAttributes['delivery-date-from'])) {
            $this->addWhereParameter($this->getBaseTable() . '.delivery_date >= %s', $searchAttributes['delivery-date-from']);
        }

        if (!empty($searchAttributes['delivery-date-to'])) {
            $this->addWhereParameter($this->getBaseTable() . '.delivery_date <= %s', $searchAttributes['delivery-date-to']);
        }

        // add join
        if ($addProductJoin) {
            $this->addJoin('left join sb_posts on ' . $this->getBaseTable() . '.product_id = sb_posts.ID and sb_posts.post_type = "product"');
        }
        if ($addCategoryJoin) {
            $this->addJoin('left join sb_term_relationships on ' . $this->getBaseTable() . '.product_id = sb_term_relationships.object_id');
            $this->addJoin('left join sb_term_taxonomy on sb_term_relationships.term_taxonomy_id = sb_term_taxonomy.term_taxonomy_id and sb_term_taxonomy.taxonomy = "product_cat"');
        }
    }

    protected function getBaseTableName()
    {
        return 'offers';
    }
}