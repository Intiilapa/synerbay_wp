<?php

namespace SynerBay;

use SynerBay\Helper\RouteHelper;
use SynerBay\Routing\Processor;
use SynerBay\Routing\Route;
use SynerBay\Routing\Router;

if (!defined('ABSPATH')) {
    return;
}

require __DIR__ . '/routing/Processor.php';
require __DIR__ . '/routing/Route.php';
require __DIR__ . '/routing/Router.php';
require __DIR__ . '/backend/helpers/RouteHelper.php';

// init routes
$router = new Router('route_synerbay');
$routes = [
    'my_plugin_test' => new Route('/test', 'synerbay_test'),
    'offer_sub_page' => new Route('/^\/offer\/([0-9]+)[\/]?$/', 'synerbay_init_global_offer_sub_page',
        get_theme_file_path() . '/pages/single-offer.php', '/offer/[id]'),
    'edit_offer' => new Route('/^\/dashboard\/edit-offer\/([0-9]+)[\/]?$/', 'synerbay_editOfferSubPage',
        get_theme_file_path() . '/dokan/templates/offers/edit-offer.php', '/dashboard/edit-offer/[id]', false),
    'show_offer' => new Route('/^\/dashboard\/show-offers\/([0-9]+)[\/]?$/', 'synerbay_init_dashboard_offer_sub_page',
        get_theme_file_path() . '/dokan/templates/offers/show-offers.php', '/dashboard/show-offers/[id]', false),
    'offer_listing' => new Route('/^\/offers[\/]?$/', 'synerbay_offerSearch',
        get_theme_file_path() . '/pages/offer/search.php', '/offers'),
//    'vendor_offers_tab' => new Route('/^\/store\/([\s\S]*)\/offers[\/]?$/', 'synerbay_initStoreTabOffers',
//        get_theme_file_path() . '/dokan/templates/store/offers-tab.php', '/store/[store_name]/offers'),
];

function my_plugin_redirect()
{
    $location = '/';

    if (!empty($_GET['location'])) {
        $location = $_GET['location'];
    }

    wp_redirect($location);
}

add_action('my_plugin_redirect', 'my_plugin_redirect', 10, 2);

Processor::init($router, $routes);

RouteHelper::setRouter($router);