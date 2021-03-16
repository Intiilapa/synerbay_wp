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
    private int $howManyDaysRegistered = 1;

    public function init()
    {
        add_action('email_marketing_rfq', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getActiveVendorsByRegisteredDate(date('Y-m-d', time() - ($this->howManyDaysRegistered * 24 * 60 * 60)));

        if (count($vendors)) {
            $mail = new \SynerBay\Emails\Service\EmailMarketing\RFQ();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}