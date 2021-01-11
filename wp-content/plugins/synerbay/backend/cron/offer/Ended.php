<?php

namespace SynerBay\Cron\Offer;

use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;

class Ended extends AbstractCron implements InterfaceCron
{
    public function init()
    {
            add_action('offer_ended_task', [$this, 'run']);
    }

    public function run()
    {
//        $mailer = new TestEmail();
//        $mailer->send('Kristóf', 'nagy.kristof.janos@gmail.com');
//        $mailer->send('Kristóf', 'mama1612@gmail.com');
//        (new TestEmail())->send('Remco', 'remcoad@gmail.com');
    }
}