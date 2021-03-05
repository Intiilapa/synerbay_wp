<?php

namespace SynerBay\Rest;

use SynerBay\Emails\Service\AbstractEmail;
use SynerBay\Emails\Service\InviteEmail;
use SynerBay\Emails\Service\InviteOfferEmail;
use SynerBay\Emails\Service\InviteProductEmail;
use SynerBay\Forms\Validators\Email;
use WeDevs\Dokan\Vendor\Vendor;

class User extends AbstractRest
{
    public function __construct()
    {
        $this->addRestRoute('inviteHeader', 'inviteHeader');
        $this->addRestRoute('inviteOffer', 'inviteOffer');
        $this->addRestRoute('inviteProduct', 'inviteProduct');
    }

    public function inviteHeader()
    {
        $this->processInvite(new InviteEmail());
    }

    public function inviteOffer()
    {
        $this->processInvite(new InviteOfferEmail());
    }

    public function inviteProduct()
    {
        $this->processInvite(new InviteProductEmail());
    }

    private function processInvite(AbstractEmail $emailService)
    {
        $postData = wp_unslash($_POST);

        $message = [];

        $name = isset($postData['invitedName']) ? $postData['invitedName'] : null;
        $email = isset($postData['invitedEmail']) ? $postData['invitedEmail'] : null;
        $inviteUrl = isset($postData['inviteUrl']) ? $postData['inviteUrl'] : null;

        if (empty($email) || !(new Email())->validate($email)) {
            $message = ['error' => 'Invalid e-mail! Please try again!'];
        }

        if (empty($name)) {
            $message = ['error' => 'Name is required! Please try again!'];
        }

        if (!get_current_user_id()) {
            $inviterName = isset($postData['inviterName']) ? $postData['inviterName'] : null;

            if (empty($inviterName)) {
                $message = ['error' => 'Your name is required! Please try again!'];
            }
        } else {
            /** @var Vendor $vendor */
            $vendor = dokan_get_vendor(get_current_user_id());
            $inviterName = $vendor->get_shop_name();
        }

        if (empty($inviteUrl)) {
            $message = ['error' => 'Something went wrong! Please refresh page and try again!'];
        }

        if (!count($message)) {
            $emailService->setParams(['url' => $inviteUrl]);
            $emailService->send($name, $email, ['inviterName' => ucfirst($inviterName)]);
            $message = ['success' => 'Invite sent!'];
        }

        $responseData = [
            'messages' => [$message],
        ];

        $this->responseSuccess($responseData);
    }
}