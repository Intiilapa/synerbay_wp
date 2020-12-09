<?php

namespace SynerBay\Traits;

trait Loader
{
    private function getModule(string $moduleName)
    {
        $class = 'SynerBay\Module\\' . ucfirst($moduleName);

        return new $class();
    }
}