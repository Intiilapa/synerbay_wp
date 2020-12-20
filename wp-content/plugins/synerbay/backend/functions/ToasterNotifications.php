<?php


namespace SynerBay\Functions;


use SynerBay\Traits\Toaster;
use SynerBay\Traits\WPAction;

class ToasterNotifications
{
    use Toaster, WPAction;

    public function __construct()
    {
        add_action('wp_footer', [$this, 'generateToasts'], 10, 2);
    }

    public function generateToasts()
    {
        $toastMessages = $this->getAndClearToastMessages();

        if (count($toastMessages)) {
            $toasts = '';

            if (array_key_exists('info', $toastMessages)) {
                $toasts .= $this->getToasts('info', $toastMessages['info']);
            }

            if (array_key_exists('success', $toastMessages)) {
                $toasts .= $this->getToasts('success', $toastMessages['success']);
            }

            if (array_key_exists('warning', $toastMessages)) {
                $toasts .= $this->getToasts('warning', $toastMessages['warning']);
            }

            if (array_key_exists('error', $toastMessages)) {
                $toasts .= $this->getToasts('error', $toastMessages['error']);
            }

            if (!empty($toasts)) {
                echo '
                    <script type="text/javascript">
                        setTimeout(() => {
                            ' . $toasts . '
                           }, 500);
                    </script>
                ';
            }
        }
    }

    private function getToasts($type, array $toasts)
    {
        $ret = '';

        foreach($toasts as $toast) {
            $ret .= $this->getToast($type, $toast);
        }

        return $ret;
    }

    private function getToast($type, $message)
    {
        return sprintf('
            window.notification.%s({
              message: "%s" 
            });
        '.PHP_EOL, $type, $message);
    }

}