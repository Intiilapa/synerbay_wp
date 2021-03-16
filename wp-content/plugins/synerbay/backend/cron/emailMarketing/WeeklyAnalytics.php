<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;

/**
 * Class WeeklyAnalytics
 * @package SynerBay\Cron\EmailMarketing
 *
 * [később]
 * Weekly analytics:
 * A user oldalának látogatottsága az adott héten.
 *
 * (hetente vagy kéthetente egyszer küldjük ki)
 * [később]
 */
class WeeklyAnalytics extends AbstractCron implements InterfaceCron
{
    public function init()
    {
        add_action('email_marketing_weekly_analytics', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A REg mail
        wc()->mailer();
    }
}