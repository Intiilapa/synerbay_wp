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

    public static function generateRoute(string $routeName, array $params = [], array $getParams = []): string
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

            $url = get_site_url() . $reverseRule;

            if (count($getParams)) {
                $url .= '?' . http_build_query($getParams);
            }

            return $url;
        }

        die('invalid route name');
    }

    public static function getRoute(string $routeName): Route
    {
        /** @var Route $route */
        if ($route = self::$router->get_route($routeName))
        {
            return $route;
        }

        die('invalid route name');
    }

    public static function addInviteCodeToUrl($url): string
    {
        if (get_current_user_id() && $inviteCode = get_user_meta( get_current_user_id(), '_invite_code', true )) {
            $url .= '?invite='.$inviteCode;
        }

        return $url;
    }

    /**
     * Get current url without parameters
     *
     * @return string
     */
    public static function getCurrentURL(): string
    {
        $protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $baseURL = $protocol . "://" . $_SERVER['HTTP_HOST'];

        return $baseURL . $_SERVER["REQUEST_URI"];
    }

}