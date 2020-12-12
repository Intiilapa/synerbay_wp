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
    protected function cleanUpdateData(array $array): array
    {
        if (!isset($this->dataMap)) {
            throw new Exception('Missing data map from module');
        }

        $ret = [];

        foreach ($array as $columnName => $value) {
            if (array_key_exists($columnName, $this->dataMap)) {
                $ret[$columnName] = $value;
            }
        }

        return $ret;
    }

    protected function getInsertFormat(array $columnKeys = []): array
    {
        if (!isset($this->dataMap)) {
            throw new Exception('Missing data map from module');
        }

        if (count($columnKeys)) {
            $formatArray = [];

            foreach (array_keys($columnKeys) as $column) {
                $formatArray[] = $this->dataMap[$column];
            }

            return $formatArray;
        }

        return array_values($this->dataMap);
    }
}