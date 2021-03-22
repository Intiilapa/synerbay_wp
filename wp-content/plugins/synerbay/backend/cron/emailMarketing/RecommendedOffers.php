<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Helper\RouteHelper;
use SynerBay\Repository\OfferRepository;
use SynerBay\Repository\ProductRepository;
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

        $offerRepository = new OfferRepository();
        $productRepository = new ProductRepository();
        $vendorRepository = new VendorRepository();

        $newOffers = $offerRepository->search([
            'visible' => true,
            'created_at_greater_equal' => date('Y-m-d', time() - (14 * 24 * 60 * 60))
        ]);

        if (count($newOffers)) {

            $offerProducts = $productRepository->getProductBaseDataWithMainCategory([
                'product_id' => array_unique(array_column($newOffers, 'product_id'))
            ]);

            $prodTemp = [];
            // át kell kulcsozni a gyorsabb elérésért
            foreach ($offerProducts as $offerProduct) {
                $offerProduct['main_category_name'] = htmlspecialchars_decode($offerProduct["main_category_name"]);
                $prodTemp[$offerProduct['id']] = $offerProduct;
            }

            // memória felszabadítás
            unset($offerProducts);

            // összeszedjük a termékeket industry típus alapján
            // INFO: a főkategória igazából a user regisztrációjakor megadott industry_type
            $newOffersByIndustry = [];

            // megszabjuk a termékek max. számát
            $maxOfferPerIndustry = 20;

            // adjuk hozzá a termék adatokat az offerekhez és egyúttal tegyük rakjuk rendbe az iparágat is
            foreach ($newOffers as $offer) {

                if (isset($prodTemp[$offer['product_id']])) {

                    $offerProduct = $prodTemp[$offer['product_id']];
                    $mainCategory = $offerProduct['main_category_name'];

                    $tempOffer = $offer;
                    $tempOffer['url'] = RouteHelper::generateRoute('offer_sub_page', ['id' => $offer['id']]);
                    $tempOffer['product'] = $offerProduct;

                    if (!array_key_exists($mainCategory, $newOffersByIndustry)) {
                        $newOffersByIndustry[$mainCategory] = [$tempOffer];
                    } else {
                        // ha túl sok a termék akkor ignoráljuk, nem küldjük ki
                        if (count($newOffersByIndustry[$mainCategory]) == $maxOfferPerIndustry) {
                            continue;
                        }

                        $newOffersByIndustry[$mainCategory][] = $tempOffer;
                    }

                }

            }

            // elkezdjük a kiküldést
            foreach ($newOffersByIndustry as $industryName => $industryOffers) {
                // összeszedjük azokat, akiknek kiküldjük az emailt
                $searchParams = [
                    'industry'   => $industryName,
                ];

                $targetVendors = $vendorRepository->getVendors($searchParams, false);

                if (count($targetVendors)) {

                    $mail = new \SynerBay\Emails\Service\EmailMarketing\RecommendedOffers(
                        [
                            'newOffers' => $industryOffers
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