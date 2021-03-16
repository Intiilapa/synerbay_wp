<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Repository\VendorRepository;

/**
 * Class RegisteredVendorNewsletter
 * @package SynerBay\Cron\EmailMarketing
 *
 *
 * New vendor has registered on SynerBay:
 *A new vendor has registered on SynerBay from /industry name/. Click to see and get connected.
 *
 *(hetente vagy heti 2x kéne lefutnia és összegyűjteni, hogy kik az új felhasználók azonos iparágból. Ha 0 akkor értelemszerűen nem küldjük ki)
 *
 *
 */
class RegisteredVendorNewsletter extends AbstractCron implements InterfaceCron
{

    public function init()
    {
        add_action('email_marketing_registered_vendors', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getActiveVendorsByRegisteredDate();

        if (count($vendors)) {
            $mail = new \SynerBay\Emails\Service\EmailMarketing\RegisteredVendorNewsletter();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}