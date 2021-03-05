<?php


namespace SynerBay\Repository;


class CurrencyRepository extends AbstractRepository
{

    public function create(string $baseCur, string $serverSyncAt, array $rates)
    {

        global $wpdb;

        $data = [
            'base_cur'       => $baseCur,
            'server_sync_at' => $serverSyncAt,
            'data'           => json_encode($rates),
        ];
        $format = ['%s', '%s', '%s'];

        return $wpdb->insert($this->getBaseTable(), $data, $format);
    }

    protected function getBaseTableName()
    {
        return 'currencies';
    }

    public function getLatestRates()
    {
        global $wpdb;

        return $wpdb->get_row('
            SELECT * FROM ' . $this->getBaseTable() . ' 
            ORDER BY created_at DESC LIMIT 1',
            ARRAY_A);
    }

    protected function prepareQuery(array $searchAttributes = [])
    {
        // TODO: Implement prepareQuery() method.
    }
}