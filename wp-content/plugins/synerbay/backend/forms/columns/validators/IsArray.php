<?php


namespace SynerBay\Forms\Validators;


class IsArray extends AbstractValidator
{
    protected function run($value)
    {
        return is_array($value);
    }

    public function error(): string
    {
        return 'Invalid type, only array type allowed!';
    }
}