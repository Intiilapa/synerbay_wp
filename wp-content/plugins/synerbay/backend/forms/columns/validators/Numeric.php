<?php


namespace SynerBay\Forms\Validators;


class Numeric extends AbstractValidator
{

    public function error(): string
    {
        return 'Invalid value! Please add a number.';
    }

    protected function run($value)
    {
        return is_numeric($value);
    }
}