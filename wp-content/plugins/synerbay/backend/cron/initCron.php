<?php

namespace SynerBay\Cron;

include_once __DIR__ . '/InterfaceCron.php';
include_once __DIR__ . '/AbstractCron.php';
include_once __DIR__ . '/offer/initOfferCron.php';
include_once __DIR__ . '/CurrencySync.php';

new CurrencySync();
