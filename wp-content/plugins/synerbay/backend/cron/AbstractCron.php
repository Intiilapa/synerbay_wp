<?php

namespace SynerBay\Cron;

abstract class AbstractCron implements InterfaceCron
{
    public function __construct()
    {
        $this->init();
    }
}