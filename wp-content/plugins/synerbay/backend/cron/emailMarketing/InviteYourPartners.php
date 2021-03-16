<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Repository\VendorRepository;

/**
 * Class InviteYourPartners
 * @package SynerBay\Cron\EmailMarketing
 *
 * Invite your partners!
 * referral üzenet, több verziót készíteni
 *
 * (hetente vagy kéthetente egyszer küldjük ki)
 */
class InviteYourPartners extends AbstractCron implements InterfaceCron
{
    private int $howManyDaysRegistered = 1;

    public function init()
    {
        add_action('email_marketing_invite_your_partners', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getActiveVendorsByRegisteredDate(date('Y-m-d', time() - ($this->howManyDaysRegistered * 24 * 60 * 60)));

        if (count($vendors)) {
            $mail = new \SynerBay\Emails\Service\EmailMarketing\InviteYourPartners();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}