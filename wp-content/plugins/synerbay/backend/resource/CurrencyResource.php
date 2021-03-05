<?php


namespace SynerBay\Resource;


class CurrencyResource extends AbstractResource
{
    protected function prepare($row): array
    {
        $rates = json_decode($row['data'], true);

        foreach ($rates as $curr => &$rate) {
            $rate = (float) $rate;
        }

        $row['data'] = $rates;
        return $row;
    }
}