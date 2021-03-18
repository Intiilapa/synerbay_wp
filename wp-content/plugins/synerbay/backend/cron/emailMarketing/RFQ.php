<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Repository\VendorRepository;

/**
 * Class RFQ
 * @package SynerBay\Cron\EmailMarketing
 */
class RFQ extends AbstractCron implements InterfaceCron
{
    public function init()
    {
        add_action('email_marketing_rfq', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getVendors([], false);

        if (count($vendors)) {
            $mail = new \SynerBay\Emails\Service\EmailMarketing\RFQ();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}