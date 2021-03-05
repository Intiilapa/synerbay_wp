<?php


namespace SynerBay\Services;

use SynerBay\Repository\CurrencyRepository;
use SynerBay\Resource\CurrencyResource;

class CurrencyService
{
    public function getCurrentRates()
    {
        // set API Endpoint and API key
        $endpoint = 'latest';
        $access_key = '26d1770d26d4dce9928ccec19636aa31';

        // Initialize CURL:
        $ch = curl_init('http://data.fixer.io/api/'.$endpoint.'?access_key='.$access_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $exchangeRates = json_decode($json, true);

        if (array_key_exists('success', $exchangeRates) && $exchangeRates['success'] == true) {
            $baseCurr = $exchangeRates['base'];

            $exchangeRates['timestamp_to_date'] = date('Y-m-d H:i:s', $exchangeRates['timestamp']);
            $exchangeRates['rates_in_usd'] = [
                'USD' => 1,
                $baseCurr => 1 / $exchangeRates['rates']['USD'],
            ];

            $supplier = 1 / $exchangeRates['rates']['USD'];
            foreach ($exchangeRates['rates'] as $currency => $rateForEur) {
                if (in_array($currency, ['USD', $baseCurr])) {
                    continue;
                }

                $exchangeRates['rates_in_usd'][$currency] = (float)$rateForEur * $supplier;
            }

            return $exchangeRates;
        }

        return false;
    }

    /**
     * TODO use for offer ended cron task
     *
     * @param float  $number
     * @param string $fromCurr
     * @param string $toCurr
     */
    public function exchange(float $number, string $fromCurr, string $toCurr = 'USD')
    {
        $lastCurrencies = $rates = (new CurrencyResource())->toArray((new CurrencyRepository())->getLatestRates());
    }
}