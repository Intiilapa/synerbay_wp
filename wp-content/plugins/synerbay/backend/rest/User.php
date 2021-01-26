<?php


namespace SynerBay\Rest;


use ReflectionException;
use SynerBay\Emails\Service\InviteEmail;
use SynerBay\Forms\Validators\Email;

class User extends AbstractRest
{
    public function __construct()
    {
        $this->addRestRoute('invite', 'invite');
    }

    public function invite()
    {
        $postData = wp_unslash($_POST);

        $message = [];

        $name = isset($postData['name']) ? $postData['name'] : null;
        $email = isset($postData['email']) ? $postData['email'] : null;
        $inviteUrl = isset($postData['inviteUrl']) ? $postData['inviteUrl'] : null;

        if (empty($inviteUrl)) {
            $message = ['error' => 'Something went wrong! Please refresh page and try again!'];
        }

        if (empty($name)) {
            $message = ['error' => 'Name is required! Please try again!'];
        }

        if (empty($email) || !(new Email())->validate($email)) {
            $message = ['error' => 'Invalid e-mail! Please try again!'];
        }

        if (!count($message)) {
            try {
                $mail = new InviteEmail(
                    ['url' => $inviteUrl]
                );
                $mail->send($name, $email);

                $message = ['success' => 'Invite sended!'];
            } catch (ReflectionException $e) {
                $message = ['error' => 'Something went wrong! Please refresh page and try again!'];
            }
        }

        $responseData = [
            'messages' => [$message],
        ];

        $this->responseSuccess($responseData);
    }
}