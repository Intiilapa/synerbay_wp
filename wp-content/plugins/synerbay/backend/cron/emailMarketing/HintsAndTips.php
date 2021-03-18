<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Repository\VendorRepository;

/**
 * Class HintsAndTips
 * @package SynerBay\Cron\EmailMarketing
 *
 * Folyamatok között ösztönzők kiküldése emailben
 * Ha a popupok bevezetése problémás akkor ezzel tudjuk helyettesíteni
 * Hints and Tips email - praktikákat kommunikálunk le a felhasználók felé
 *
 * (minden új felhasználónak 1x küldjük ki, 1 nappal a regisztráció után)
 */
class HintsAndTips extends AbstractCron implements InterfaceCron
{
    private int $howManyDaysRegistered = 1;

    public function init()
    {
        add_action('email_marketing_hints_and_tips', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getActiveVendorsByRegisteredDate(date('Y-m-d', time() - ($this->howManyDaysRegistered * 24 * 60 * 60)));

        if (count($vendors)) {
            $mail = new \SynerBay\Emails\Service\EmailMarketing\HintsAndTips();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}