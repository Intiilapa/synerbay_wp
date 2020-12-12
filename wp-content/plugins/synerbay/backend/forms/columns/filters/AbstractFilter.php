<?php


namespace SynerBay\Forms\Filters;


abstract class AbstractFilter
{
    public function getFilteredValue($value)
    {
        return $this->filter($value);
    }

    abstract protected function filter($value);
}