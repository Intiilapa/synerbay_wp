<?php


namespace SynerBay\Cron\EmailMarketing;

use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Repository\VendorRepository;

/**
 * Class SurplusStock
 * @package SynerBay\Cron\EmailMarketing
 */
class SurplusStock extends AbstractCron implements InterfaceCron
{
    public function init()
    {
        add_action('email_marketing_surplus_stock', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getVendors([], false);

        if (count($vendors)) {
            $mail = new \SynerBay\Emails\Service\EmailMarketing\SurplusStock();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}