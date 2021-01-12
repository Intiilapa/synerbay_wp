<?php

namespace SynerBay\Repository;


use SynerBay\Traits\Search;

abstract class AbstractRepository
{
    use Search;

    public function delete($where, $whereFormat = null)
    {
        global $wpdb;
        return $wpdb->delete($this->getBaseTable(), $where, $whereFormat);
    }

    abstract protected function prepareQuery(array $searchAttributes = []);
    abstract protected function getBaseTableName();
}