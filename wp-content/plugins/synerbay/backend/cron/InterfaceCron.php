<?php

namespace SynerBay\Cron;

interface InterfaceCron
{
    public function init();
    public function run();
}
