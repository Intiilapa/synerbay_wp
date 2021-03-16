<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Repository\VendorRepository;
use SynerBay\Emails\Service\EmailMarketing\CompleteYourCatalogue as CompleteYourCatalogueMail;

/**
 * Class CompleteYourCatalogue
 * @package SynerBay\Cron\EmailMarketing
 *
 * Complete your catalogue:
 * Why it is important to complete your catalogue-»product-»rfq-»new connection
 *
 * (minden új felhasználónak 1x küldjük ki)
 */
class CompleteYourCatalogue extends AbstractCron implements InterfaceCron
{
    private int $howManyDaysRegistered = 1;

    public function init()
    {
        add_action('email_marketing_complete_your_catalogue', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $vendors = (new VendorRepository())->getActiveVendorsByRegisteredDate(date('Y-m-d', time() - ($this->howManyDaysRegistered * 24 * 60 * 60)));

        if (count($vendors)) {
            $mail = new CompleteYourCatalogueMail();
            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}