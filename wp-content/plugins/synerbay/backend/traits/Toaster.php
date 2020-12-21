<?php

namespace SynerBay\Traits;

trait Toaster
{
    use Sys;

    protected function addInfoToast($message)
    {
        $this->addToastMessage('info', $message);
    }

    protected function addSuccessToast($message)
    {
        $this->addToastMessage('success', $message);
    }

    protected function addWarningToast($message)
    {

        $this->addToastMessage('warning', $message);
    }

    protected function addErrorToast($message)
    {
        $this->addToastMessage('error', $message);
    }

    private function addToastMessage($type, $message)
    {
        if (!$this->isCLIRunMode()) {
            if (!isset($_SESSION['toast_messages'][$type])) {
                $_SESSION['toast_messages'][$type] = [];
            }

            $_SESSION['toast_messages'][$type][] = $message;
        }
    }

    public function getAndClearToastMessages()
    {
        if (isset($_SESSION['toast_messages']) && count($_SESSION['toast_messages'])) {
            $ret =  $_SESSION['toast_messages'];
        } else {
            $ret = [];
        }

        unset($_SESSION['toast_messages']);

        return $ret;
    }
}