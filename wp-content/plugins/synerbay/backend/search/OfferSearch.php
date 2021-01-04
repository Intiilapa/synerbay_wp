<?php


namespace SynerBay\Search;


class OfferSearch extends AbstractSearch
{

    protected function prepareQuery()
    {
        global $wpdb;

        $query = 'select '.$wpdb->prefix.'offers.id from '.$wpdb->prefix.'offers';
        $where = '';

        if (isset($this->searchAttributes['recent_offers'])) {
            $currentDate = date('Y-m-d H:i:s');

            $where .= $wpdb->prefix.'offers.offer_start_date <= "'.$currentDate.'" AND ' . $wpdb->prefix.'offers.offer_end_date >= "'.$currentDate.'"';
        }

        if (!empty($where)) {
            $query .= ' WHERE ' . $where;
        }

        $this->query = $query;
    }
}