<?php


namespace SynerBay\Forms\Filters;


class EndDay extends AbstractFilter
{

    protected function filter($value)
    {
        return !empty($value) ? date('Y-m-d', strtotime($value)) . ' 23:59:59' : $value;
    }
}