<?php


namespace SynerBay\Forms\Validators;


class Integer extends AbstractValidator
{

    public function error(): string
    {
        return 'Invalid value! Please add a valid number. Example: 1, 10, 100, etc.';
    }

    protected function run($value)
    {
        return ctype_digit($value) && $value > 0;
    }
}