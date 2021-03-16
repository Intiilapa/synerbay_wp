<?php

namespace SynerBay\Functions;

use SynerBay\Cron\EmailMarketing\CompleteYourCatalogue;
use SynerBay\Repository\VendorRepository;
use SynerBay\Traits\Loader;
use SynerBay\Traits\Memcache;
use SynerBay\Traits\Toaster;
use SynerBay\Traits\WPAction;

class Test
{
    use WPAction, Loader, Toaster, Memcache;

    public function __construct()
    {
        $this->addAction('test');
    }

    public function test()
    {
        // latest currency rates
//        print '<pre>';
//        var_dump((new CurrencyResource())->toArray((new CurrencyRepository())->getLatestRates()));
//        die;

        // cron test
//        global $wpdb;
//        $categories = SynerBayDataHelper::getCategories();
//        $relations = [];
////        print '<pre>';
//        foreach ($categories as $categoryName => $subcategories) {
//
//            $catInsertData = [
//                'name' => $categoryName,
//                'slug' => sanitize_title($categoryName),
//                'term_group' => 0,
//            ];
//
//            $wpdb->insert('sb_terms', $catInsertData, ['%s', '%s', '%d']);
//            $categoryID = $wpdb->insert_id;
//
//            $relations[] = [
//                'term_id' => $categoryID,
//                'taxonomy' => 'product_cat',
//                'parent' => 0,
//            ];
//
//
//            foreach ($subcategories as $subcategoryName => $subSubCategories) {
////                var_dump($subcategoryName);
//
//                $catInsertData = [
//                    'name' => $subcategoryName,
//                    'slug' => sanitize_title($subcategoryName),
//                    'term_group' => 0,
//                ];
//
//                $wpdb->insert('sb_terms', $catInsertData, ['%s', '%s', '%d']);
//                $subCategoryID = $wpdb->insert_id;
//
//                $relations[] = [
//                    'term_id' => $subCategoryID,
//                    'taxonomy' => 'product_cat',
//                    'parent' => $categoryID,
//                ];
//
//
//                foreach ($subSubCategories as $cat) {
////                    var_dump($cat);
//                    $catInsertData = [
//                        'name' => $cat,
//                        'slug' => sanitize_title($cat),
//                        'term_group' => 0,
//                    ];
//
//                    $wpdb->insert('sb_terms', $catInsertData, ['%s', '%s', '%d']);
//                    $catID = $wpdb->insert_id;
//
//                    $relations[] = [
//                        'term_id' => $catID,
//                        'taxonomy' => 'product_cat',
//                        'parent' => $subCategoryID,
//                    ];
//
//                }
//            }
//        }
//
//        foreach ($relations as $relation) {
//            $wpdb->insert('sb_term_taxonomy', $relation, ['%d', '%s', '%d']);
//        }

//            var_dump($subcategories);
//        die('vége');
//        do_action('offer_ended_task');

//        // cache test
//        $data = 'alma';
//
//        $this->setCacheData('test', 'a', $data);
//        $this->setCacheData('test', 'a2', $data);
//        $this->setCacheData('test', 'a3', $data);
//        $this->setCacheData('test2', 'a', $data);
//        $this->setCacheData('test2', 'a2', $data);
//        $this->setCacheData('test2', 'a3', $data);
//
//        $this->getAllStoredValue();
//
//        var_dump($this->getCacheData('test2', 'a3'));
//        $this->deleteCacheData('test2', 'a3');
//        var_dump($this->getCacheData('test2', 'a3'));
//
//        $this->getAllStoredValue();
//
//        $this->deleteGroupFromCache('test');
//
//        $this->getAllStoredValue();
//
//        $this->deleteGroupFromCache('test2');
//
//        var_dump($this->getCacheData('test2', 'a3'));
//        var_dump($this->getCacheData('test2', 'a2'));
//        var_dump($this->getCacheData('test', 'a2'));
//        $this->getAllStoredValue();
//        die;

//        do_action('email_marketing_complete_your_catalogue');
//        var_dump(date('Y-m-d', time() - (16 * 24 * 60 * 60)));
//        (new VendorRepository())->getActiveVendorsByRegisteredDate('2021-02-15');
//        die;
    }
}