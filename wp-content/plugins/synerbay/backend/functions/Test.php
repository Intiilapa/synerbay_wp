<?php

namespace SynerBay\Functions;

use SynerBay\Traits\Loader;
use SynerBay\Traits\Toaster;
use SynerBay\Traits\WPAction;

class Test
{
    use WPAction, Loader, Toaster;

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
    }
}