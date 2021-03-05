<?php


namespace SynerBay\Search;


class OfferSearch extends AbstractSearch
{
    protected function prepareQuery()
    {
        global $wpdb;

        $this->columns = [$wpdb->prefix.'offers.id'];

        if (isset($this->searchAttributes['recent_offers'])) {
            $currentDate = date('Y-m-d H:i:s');
            $this->addWhereParameter($wpdb->prefix.'offers.offer_start_date <= %s', $currentDate);
            $this->addWhereParameter($wpdb->prefix.'offers.offer_end_date >= %s', $currentDate);
        }

        if (isset($this->searchAttributes['my_offers'])) {
            $this->addWhereParameter($wpdb->prefix.'offers.user_id = %d', get_current_user_id());
        }
    }

    protected function getBaseTableName()
    {
        return 'offers';
    }
}