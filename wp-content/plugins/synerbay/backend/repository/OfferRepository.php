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

    protected function prepareQuery(array $searchAttributes = [])
    {
        if (isset($searchAttributes['recent_offers'])) {
            $currentDate = date('Y-m-d H:i:s');
            $this->addWhereParameter($this->getBaseTable() . '.offer_start_date <= %s', $currentDate);
            $this->addWhereParameter($this->getBaseTable() . '.offer_end_date >= %s', $currentDate);
        }

        if (isset($searchAttributes['my_offers'])) {
            $this->addWhereParameter($this->getBaseTable() . '.user_id = %d', get_current_user_id());
        }

        if (isset($searchAttributes['product_id'])) {
            $product = $searchAttributes['product_id'];

            if (!is_array($product)) {
                $product = [$product];
            }

            $this->addWhereParameter($this->getBaseTable() . '.product_id in (%s)', implode(', ', $product));
        }
    }

    protected function getBaseTableName()
    {
        return 'offers';
    }
}