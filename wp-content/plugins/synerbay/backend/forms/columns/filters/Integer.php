<?php


namespace SynerBay\Forms\Filters;


class Integer extends AbstractFilter
{
    protected function filter($value)
    {
        return (int)$value;
    }

}