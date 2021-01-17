<?php

namespace SynerBay\Cron;

abstract class AbstractCron implements InterfaceCron
{
    protected array $cachedVendors = [];

    public function __construct()
    {
        $this->init();
    }

    protected function getVendor(int $userID)
    {
        if (!array_key_exists($userID, $this->cachedVendors)) {
            $this->cachedVendors[$userID] = dokan_get_vendor($userID);
        }

        return $this->cachedVendors[$userID];
    }
}