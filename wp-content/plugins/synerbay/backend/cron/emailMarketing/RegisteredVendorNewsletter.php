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
 * A new vendor has registered on SynerBay from /industry name/. Click to see and get connected.
 *
 *(hetente vagy heti 2x kéne lefutnia és összegyűjteni, hogy kik az új felhasználók azonos iparágból. Ha 0 akkor értelemszerűen nem küldjük ki)
 *
 *
 */
class RegisteredVendorNewsletter extends AbstractCron implements InterfaceCron
{
    private int $howManyDaysRegistered = 7;

    public function init()
    {
        add_action('email_marketing_registered_vendors', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();
        $repository = new VendorRepository();

        $searchParams = [
            'registered_date_greater_equal' => date('Y-m-d', time() - ($this->howManyDaysRegistered * 24 * 60 * 60))
        ];

        $newVendors = $repository->getVendors($searchParams);

        if (count($newVendors)) {

            // összeszedjük a usereket
            $newVendorsByIndustry = [];

            // megszabjuk az új felhasználók max. számát a mail-ben
            $maxNewUserPerIndustry = 20;

            foreach ($newVendors as $newVendor) {
                if (isset($newVendor['data']['dokan_profile_settings']['vendor_industry']) && !empty($newVendor['data']['dokan_profile_settings']['vendor_industry'])) {
                    $vendorIndustry = $newVendor['data']['dokan_profile_settings']['vendor_industry'];

                    if (!array_key_exists($vendorIndustry, $newVendorsByIndustry)) {
                        $newVendorsByIndustry[$vendorIndustry] = [$newVendor];
                    } else {
                        // ha túl sok a felhasználó akkor ignoráljuk, nem küldjük ki
                        if (count($newVendorsByIndustry[$vendorIndustry]) == $maxNewUserPerIndustry) {
                            continue;
                        }

                        $newVendorsByIndustry[$vendorIndustry][] = $newVendor;
                    }
                }
            }

            foreach ($newVendorsByIndustry as $industryName => $industryVendors) {
                // akiknek kiküldjük az emailt
                $targetVendors = $repository->getVendors(
                    [
                        'except_ids' => array_column($newVendors, 'ID'),
                        'industry'   => $industryName
                    ], false
                );

                if (count($targetVendors)) {

                    $mail = new \SynerBay\Emails\Service\EmailMarketing\RegisteredVendorNewsletter(
                        [
                            'industryName' => $industryName,
                            'newVendors' => $industryVendors
                        ]
                    );

                    foreach ($targetVendors as $vendor) {
                        $mail->send($vendor['display_name'], $vendor['user_email']);
                    }
                }
            }
        }
    }
}