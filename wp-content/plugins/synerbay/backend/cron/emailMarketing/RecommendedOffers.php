<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Repository\VendorRepository;

/**
 * Class RecommendedOffers
 * @package SynerBay\Cron\EmailMarketing
 *
 * Recommended for you:
 * Releváns ajánlatok kiküldése
 *
 * (hetente vagy kéthetente egyszer küldjük ki)
 */
class RecommendedOffers extends AbstractCron implements InterfaceCron
{

    public function init()
    {
        add_action('email_marketing_recommended_offers', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getActiveVendorsByRegisteredDate();

        if (count($vendors)) {
            $mail = new \SynerBay\Emails\Service\EmailMarketing\RecommendedOffers();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}