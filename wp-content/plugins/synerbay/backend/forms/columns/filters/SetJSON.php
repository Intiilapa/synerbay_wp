<?php


namespace SynerBay\Forms\Filters;


class SetJSON extends AbstractFilter
{
    protected function filter($value)
    {
        return json_encode($value);
    }
}