<?php


namespace SynerBay\Module;


use SynerBay\Traits\Memcache;

abstract class AbstractModule
{
    use Memcache;

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