<?php

namespace SynerBay\Traits;

use Exception;

trait Module
{
    protected function cleanData(array $array): array
    {
        if (!isset($this->dataMap)) {
            throw new Exception('Missing data map from module');
        }

        $ret = [];

        foreach (array_keys($this->dataMap) as $columnName) {
            $ret[$columnName] = isset($array[$columnName]) ? $array[$columnName] : null;
        }

        return $ret;
    }

    protected function getInsertFormat(): array
    {
        if (!isset($this->dataMap)) {
            throw new Exception('Missing data map from module');
        }

        return array_values($this->dataMap);
    }
}