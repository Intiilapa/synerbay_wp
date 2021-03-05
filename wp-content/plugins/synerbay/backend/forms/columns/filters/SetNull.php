<?php


namespace SynerBay\Forms\Filters;


class SetNull extends AbstractFilter
{
    protected function filter($value)
    {
        return empty($value) ? null : $value;
    }
}