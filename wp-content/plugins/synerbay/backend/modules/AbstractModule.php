<?php


namespace SynerBay\Module;


abstract class AbstractModule
{
    private string $errorMessage = '';

    protected function addErrorMsg(string $message): void
    {
        $this->errorMessage = $message;
    }

    public function getErrorMessage(): string
    {
        $retMsg = !empty($this->errorMessage) ? $this->errorMessage : 'Something went wrong! Please try again!';
        $this->errorMessage = '';
        return $retMsg;
    }

}