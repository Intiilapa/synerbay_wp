<?php


namespace SynerBay\Forms\Validators;


class Email extends AbstractValidator
{
    public function error(): string
    {
        return 'Invalid e-mail!';
    }

    protected function run($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}