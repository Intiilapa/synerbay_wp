<?php
namespace SynerBay\Rest;

use SynerBay\Traits\WPActionLoader;
use WP_User;

class AbstractRest
{
    use WPActionLoader;

    /**
     * @return void
     */
    protected function checkLogin()
    {
        if (!$this->getCurrentUserID()) {
            $this->getPleaseLoginResponse();
        }
    }

    protected function getCurrentUserID()
    {
        return get_current_user_id();
    }

    protected function getPleaseLoginResponse()
    {
        wp_send_json_success(['loginRequired' => true], 200);
    }

    protected function responseSuccess(array $responseData): void
    {
        wp_send_json_success($responseData, 200);
    }

    /**
     * @return false|WP_User|null
     */
    protected function getCurrentUser()
    {
        if (!$this->getCurrentUserID()) {
            return false;
        }

        return get_userdata($this->getCurrentUserID());
    }
}