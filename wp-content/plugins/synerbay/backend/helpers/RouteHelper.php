<?php


namespace SynerBay\Helper;


use SynerBay\Routing\Route;
use SynerBay\Routing\Router;

class RouteHelper
{
    /**
     * @var Router
     */
    public static Router $router;

    public static function setRouter(Router $router)
    {
        self::$router = $router;
    }

    public static function generateRoute(string $routeName, array $params = [])
    {
        /** @var Route $route */
        if ($route = self::$router->get_route($routeName))
        {
            $reverseRule = $route->has_reverse() ? $route->get_reverse() : $route->get_path();
            if (count($params)) {
                foreach ($params as $reverseRuleParam => $ruleValue) {
                    $reverseRule = str_replace(sprintf('[%s]', $reverseRuleParam), $ruleValue, $reverseRule);
                }
            }

            return get_site_url() . $reverseRule;
        }

        die('invalid route name');
    }

    public static function getRoute(string $routeName)
    {
        /** @var Route $route */
        if ($route = self::$router->get_route($routeName))
        {
            return $route;
        }

        die('invalid route name');
    }
}