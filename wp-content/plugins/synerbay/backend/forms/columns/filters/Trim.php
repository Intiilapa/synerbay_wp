<?php


namespace SynerBay\Forms\Filters;


class Trim extends AbstractFilter
{
    protected function filter($value)
    {
        return trim($value);
    }
}