<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Repository\VendorRepository;

/**
 * Class B2BCrowdfunding
 * @package SynerBay\Cron\EmailMarketing
 *
 * (2 havonta küldjük ki egyszer)
 */
class B2BCrowdfunding extends AbstractCron implements InterfaceCron
{
    public function init()
    {
        add_action('email_marketing_b2b_crowdfunding', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getVendors([], false);

        if (count($vendors)) {
            $mail = new \SynerBay\Emails\Service\EmailMarketing\B2BCrowdfunding();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}