<?php

namespace SynerBay\Routing;

use WP;
use WP_Error;

/**
 * The Processor is in charge of the interaction between the routing system and
 * the rest of WordPress.
 */
class Processor
{
    /**
     * The matched route found by the router.
     *
     * @var Route
     */
    private $matched_route;

    /**
     * @var string
     */
    private $matched_route_name = '';

    /**
     * The router.
     *
     * @var Router
     */
    private $router;

    /**
     * The routes we want to register with WordPress.
     *
     * @var Route[]
     */
    private $routes;

    /**
     * Constructor.
     *
     * @param Router  $router
     * @param Route[] $routes
     */
    public function __construct(Router $router, array $routes = array())
    {
        $this->router = $router;
        $this->routes = $routes;
    }

    /**
     * Initialize processor with WordPress.
     *
     * @param Router  $router
     * @param Route[] $routes
     */
    public static function init(Router $router, array $routes = array())
    {
        $self = new self($router, $routes);

        add_action('init', array($self, 'register_routes'));
        add_action('parse_request', array($self, 'match_request'));
        add_action('template_include', array($self, 'load_route_template'));
        add_action('template_redirect', array($self, 'call_route_hook'));
        add_action('pre_get_document_title', array($self, 'get_page_title'));
    }

    /**
     * Checks to see if a route was found. If there's one, it calls the route hook.
     */
    public function call_route_hook()
    {
        if (!$this->matched_route instanceof Route || !$this->matched_route->has_hook()) {
            return;
        }

        if (@preg_match($this->matched_route->get_path(), $_SERVER['REQUEST_URI'], $data)) {
            do_action($this->matched_route->get_hook(), count($data) == 2 ? $data[1] : $data);
        } else {
            do_action($this->matched_route->get_hook());
        }
    }

    /**
     * Checks to see if a route was found. If there's one, it loads the route template.
     *
     * @param string $template
     *
     * @return string
     */
    public function load_route_template($template)
    {
        if (!$this->matched_route instanceof Route || !$this->matched_route->has_template() || !$this->matched_route->load_template()) {
            return $template;
        }

        if (!is_file($this->matched_route->get_template())) {
            $route_template = get_query_template($this->matched_route->get_template());
        } else {
            $route_template = $this->matched_route->get_template();
        }

        if (!empty($route_template)) {
            $template = $route_template;
        }

        return $template;
    }

    /**
     * Attempts to match the current request to a route.
     *
     * @param WP $environment
     */
    public function match_request(WP $environment)
    {
        /** @var Route $route */
        foreach($this->routes as $route_name => $route) {
            if (@preg_match($route->get_path(), $_SERVER['REQUEST_URI'])) {
                $matched_route = $this->routes[$route_name];
                $this->matched_route_name = $route_name;
                break;
            }
        }

        if (!isset($matched_route) || !$matched_route instanceof Route) {
            $matched_route = $this->router->match($environment->query_vars);
            $this->matched_route_name = $this->router->get_matched_route_name();
        }

        if ($matched_route instanceof Route) {
            $this->matched_route = $matched_route;
        }

        if ($matched_route instanceof WP_Error && in_array('route_not_found', $matched_route->get_error_codes())) {
            wp_die($matched_route, 'Route Not Found', array('response' => 404));
        }
    }

    /**
     * Register all our routes into WordPress.
     */
    public function register_routes()
    {
        $routes = apply_filters('my_plugin_routes', $this->routes);

        foreach ($routes as $name => $route) {
            $this->router->add_route($name, $route);
        }

        $this->router->compile();

        $routes_hash = md5(serialize($routes));

        if ($routes_hash != get_option('my_plugin_routes_hash')) {
            flush_rewrite_rules();
            update_option('my_plugin_routes_hash', $routes_hash);
        }
    }

    /**
     * @param $title
     * @return string
     */
    public function get_page_title($title)
    {
        $routeTitle = Route::getTitle($this->matched_route_name);

        if ($routeTitle != 'SynerBay') {
            $title = $routeTitle;
        }

        return $title;
    }
}