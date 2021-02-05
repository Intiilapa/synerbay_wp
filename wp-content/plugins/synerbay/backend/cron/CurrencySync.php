<?php


namespace SynerBay\Cron;

use SynerBay\Repository\CurrencyRepository;
use SynerBay\Services\CurrencyService;

class CurrencySync extends AbstractCron implements InterfaceCron
{

    public function init()
    {
        add_action('currency_sync_task', [$this, 'run']);
    }

    public function run()
    {
        $currencyService = new CurrencyService();

        if ($data = $currencyService->getCurrentRates()) {
            $currencyRepository = new CurrencyRepository();

            return $currencyRepository->create('USD', $data['timestamp_to_date'], $data['rates_in_usd']);
        }

        return false;
    }
}