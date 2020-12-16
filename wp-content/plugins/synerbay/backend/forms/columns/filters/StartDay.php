<?php

namespace SynerBay\Forms\Filters;


class StartDay extends AbstractFilter
{
    protected function filter($value)
    {
        return !empty($value) ? date('Y-m-d', strtotime($value)) . ' 00:00:00' : $value;
    }
}