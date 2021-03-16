<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Repository\VendorRepository;

/**
 * Class HowToReachCustomers
 * @package SynerBay\Cron\EmailMarketing
 *
 * How to reach global customers:
 * Upload, offer, share on social media... stb
 *
 * (minden új felhasználónak 1x küldjük ki)
 */
class HowToReachCustomers extends AbstractCron implements InterfaceCron
{
    private int $howManyDaysRegistered = 1;

    public function init()
    {
        add_action('email_marketing_how_to_reach_customers', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getActiveVendorsByRegisteredDate(date('Y-m-d', time() - ($this->howManyDaysRegistered * 24 * 60 * 60)));

        if (count($vendors)) {
            $mail = new \SynerBay\Emails\Service\EmailMarketing\HowToReachCustomers();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}