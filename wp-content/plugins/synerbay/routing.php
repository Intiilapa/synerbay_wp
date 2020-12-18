<?php

namespace SynerBay;

use SynerBay\Routing\Processor;
use SynerBay\Routing\Route;
use SynerBay\Routing\Router;

if (!defined('ABSPATH')) {
    return;
}

require __DIR__ . '/routing/Processor.php';
require __DIR__ . '/routing/Route.php';
require __DIR__ . '/routing/Router.php';

// init routes
$router = new Router('route_synerbay');
$routes = [
    'my_plugin_index'    => new Route('/offer', 'synerbay_init_global_offer_by_id',
        get_theme_file_path() . '/pages/offerSubPage.php'),
    'my_plugin_redirect' => new Route('/offer/redirect', 'my_plugin_redirect'),
    'my_plugin_test'     => new Route('/offer/test', 'synerbay_test'),
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