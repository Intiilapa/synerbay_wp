<?php

namespace SynerBay\Repository;


use SynerBay\Traits\Search;

abstract class AbstractRepository
{
    use Search;

    abstract protected function prepareQuery(array $searchAttributes = []);
    abstract protected function getBaseTableName();
}