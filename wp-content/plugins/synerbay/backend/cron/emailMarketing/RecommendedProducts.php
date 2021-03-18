<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Repository\ProductRepository;
use SynerBay\Repository\VendorRepository;

/**
 * Class RecommendedProducts
 * @package SynerBay\Cron\EmailMarketing
 *
 * (Kéthetente egyszer küldjük ki azokat a termékeket, amelyek azonos iparágban vannak mint a user. Ha nem jelent meg új termék akkor értelemszerűen ne küldünk ki üres emailt)
 */
class RecommendedProducts extends AbstractCron implements InterfaceCron
{
    public function init()
    {
        add_action('email_marketing_recommended_products', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();
        $productRepository = new ProductRepository();
        $vendorRepository = new VendorRepository();

        $newProducts = $productRepository->getProductBaseDataWithMainCategory([
            'is_active' => true,
            'created_at_greater_equal' => date('Y-m-d', time() - (14 * 24 * 60 * 60))
        ]);

        if (count($newProducts)) {

            // összeszedjük a termékeket industry típus alapján
            // INFO: a főkategória igazából a user regisztrációjakor megadott industry_type
            $newProductsByIndustry = [];

            // megszabjuk a termékek max. számát
            $maxProductPerIndustry = 20;

            foreach ($newProducts as $newProduct) {
                $mainCategory = htmlspecialchars_decode($newProduct["main_category_name"]);

                if (!array_key_exists($mainCategory, $newProductsByIndustry)) {
                    $newProductsByIndustry[$mainCategory] = [$newProduct];
                } else {
                    // ha túl sok a termék akkor ignoráljuk, nem küldjük ki
                    if (count($newProductsByIndustry[$mainCategory]) == $maxProductPerIndustry) {
                        continue;
                    }

                    $newProductsByIndustry[$mainCategory][] = $newProduct;
                }
            }

        }

        // elkezdjük a kiküldést
        foreach ($newProductsByIndustry as $industryName => $industryProducts) {
            // összeszedjük azokat, akiknek kiküldjük az emailt
            $searchParams = [
                'industry'   => $industryName,
            ];

            $targetVendors = $vendorRepository->getVendors($searchParams, false);

            if (count($targetVendors)) {

                $mail = new \SynerBay\Emails\Service\EmailMarketing\RecommendedProducts(
                    [
                        'newProducts' => $industryProducts
                    ]
                );

                foreach ($targetVendors as $vendor) {
                    $mail->send($vendor['display_name'], $vendor['user_email']);
                }
            }
        }
    }
}