<?php


namespace SynerBay\Forms\Filters;


class MysqlSet extends AbstractFilter
{
    protected function filter($value)
    {
        return implode(',', $value);
    }
}