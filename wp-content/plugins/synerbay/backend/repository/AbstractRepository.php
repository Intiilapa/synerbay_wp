<?php

namespace SynerBay\Repository;

use ReflectionClass;
use ReflectionException;
use SynerBay\Traits\Search;

abstract class AbstractRepository
{
    use Search;

    protected $primaryKey = 'id';

    public function delete($where, $whereFormat = null)
    {
        global $wpdb;
        return $wpdb->delete($this->getBaseTable(), $where, $whereFormat);
    }

    public function getRowByPrimaryKey($primaryKeyValue)
    {
        try {
            $class = (new ReflectionClass($this))->getShortName();
        } catch (ReflectionException $e) {
            $class = get_called_class();
        }

        $key = $class . '_' . $primaryKeyValue;

        if (!isset($GLOBALS[$key])) {
            global $wpdb;
            $GLOBALS[$key] = $wpdb->get_row('
            SELECT * FROM ' . $this->getBaseTable() . ' 
            where 
            '.$this->primaryKey.' = ' . $primaryKeyValue,
                ARRAY_A);
        }

        return $GLOBALS[$key];
    }

    abstract protected function prepareQuery(array $searchAttributes = []);
    abstract protected function getBaseTableName();
}