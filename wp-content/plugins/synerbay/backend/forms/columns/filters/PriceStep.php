<?php


namespace SynerBay\Forms\Filters;


class PriceStep extends AbstractFilter
{
    protected function filter($value)
    {
        return $this->sortValues($value);
    }

    private function sortValues($value)
    {
        $tmp = [];
        foreach ($value as $step) {
            $tmp[$step['qty']] = $step;
        }

        ksort($tmp);
        $ret = [];

        foreach ($tmp as $step) {
            $ret[] = $step;
        }

        return $ret;
    }
}