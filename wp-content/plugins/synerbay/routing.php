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
/** @var Router $router */
$router = new Router('route_synerbay');
$routes = [
    'my_plugin_test' => new Route('/offer/test', 'synerbay_test'),
    'offer_sub_page' => new Route('/^\/offer\/([0-9]+)[\/]?$/', 'synerbay_init_global_offer_sub_page',
        get_theme_file_path() . '/pages/offerSubPage.php', '/offer/[id]'),
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