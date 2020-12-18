<?php

namespace SynerBay\Traits;

trait Sys
{
    public function isCLIRunMode()
    {
        return php_sapi_name() == 'cli';
    }
}