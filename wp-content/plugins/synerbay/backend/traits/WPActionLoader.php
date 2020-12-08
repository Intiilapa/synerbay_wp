<?php
namespace SynerBay\Traits;

trait WPActionLoader
{
    public function addAction($functionName)
    {
        add_action('synerbay_' . $functionName, [$this, $functionName]);
    }

    public function addRestRoute(string $callback, string $endpointName, $methods = 'POST', $permissionCallback = 'defaultPermissionCallback')
    {
        add_action('rest_api_init', function () use ($callback, $endpointName, $methods, $permissionCallback){
            register_rest_route('synerbay/api/v1', '/'. $endpointName .'/', [
                'methods'  => $methods,
                'callback' => [$this, $callback],
                'permission_callback' => $this->$permissionCallback(),
            ]);
        });
    }
}