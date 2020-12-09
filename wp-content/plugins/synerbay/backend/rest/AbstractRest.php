<?php
namespace SynerBay\Rest;

use SynerBay\Traits\WPAction;
use WP_User;

/**
 * Class AbstractRest
 *
 * @package SynerBay\Rest
 */
class AbstractRest
{
    use WPAction;

    /**
     * Default permission callback function for rest routes
     *
     * @return false
     */
    public function defaultPermissionCallback(): bool
    {
        return false;
    }

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
        $this->responseSuccess(['loginRequired' => true]);
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