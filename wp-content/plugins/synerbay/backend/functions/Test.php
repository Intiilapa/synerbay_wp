<?php

namespace SynerBay\Functions;

use SynerBay\Traits\Loader;
use SynerBay\Traits\Toaster;
use SynerBay\Traits\WPAction;

class Test
{
    use WPAction, Loader, Toaster;

    public function __construct()
    {
        $this->addAction('test');
    }

    public function test()
    {
        // cron test
        do_action('offer_started_task');
    }
}