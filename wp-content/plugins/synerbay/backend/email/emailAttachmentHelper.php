<?php


namespace SynerBay\Emails;


class EmailAttachmentHelper
{
    /**
     * Fontos, hogy a kiinduló (attachment) mappához viszonytva add meg a file teljes nevét
     *
     * @param string $file
     * @return false|string
     */
    public static function getAttachmentPath(string $file)
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . ltrim($file, DIRECTORY_SEPARATOR);

        if (is_file($filePath)) {
            return $filePath;
        }

        return false;
    }
}