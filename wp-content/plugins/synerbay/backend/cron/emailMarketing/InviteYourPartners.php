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
    public function init()
    {
        add_action('email_marketing_invite_your_partners', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getVendors([], false);

        if (count($vendors)) {
            $mail = new \SynerBay\Emails\Service\EmailMarketing\InviteYourPartners();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}