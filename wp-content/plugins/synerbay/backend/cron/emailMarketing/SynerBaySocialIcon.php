<?php


namespace SynerBay\Cron\EmailMarketing;


use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Emails\EmailAttachmentHelper;
use SynerBay\Repository\VendorRepository;

/**
 * Class SynerBaySocialIcon
 * @package SynerBay\Cron\EmailMarketing
 */
class SynerBaySocialIcon extends AbstractCron implements InterfaceCron
{
    private int $howManyDaysRegistered = 3;

    public function init()
    {
        add_action('email_marketing_synerbay_social_icon', [$this, 'run']);
    }

    public function run()
    {
        // EZ KELL !!! HA NEM ITT HÍVOD? AKKOR ELKÚSZIK A MAIL
        wc()->mailer();

        $searchParams = [
            'registered_date' => date('Y-m-d', time() - ($this->howManyDaysRegistered * 24 * 60 * 60)),
        ];

        $vendors = (new VendorRepository())->getVendors($searchParams);

        $attachmentFilePathSkeleton = 'static' . DIRECTORY_SEPARATOR . '%s';

        if (count($vendors)) {

            $attachments = [
                EmailAttachmentHelper::getAttachmentPath(sprintf($attachmentFilePathSkeleton, 'synerbay_follow_us.png')),
                EmailAttachmentHelper::getAttachmentPath(sprintf($attachmentFilePathSkeleton, 'synerbay_logo_round.png')),
                EmailAttachmentHelper::getAttachmentPath(sprintf($attachmentFilePathSkeleton, 'synerbay_logo_rounded.png')),
                EmailAttachmentHelper::getAttachmentPath(sprintf($attachmentFilePathSkeleton, 'synerbay_logo_square.png')),
            ];

            $mail = new \SynerBay\Emails\Service\EmailMarketing\SynerBaySocialIcon([], $attachments);

            foreach ($vendors as $vendor) {
                $mail->send($vendor['display_name'], $vendor['user_email']);
            }
        }
    }
}