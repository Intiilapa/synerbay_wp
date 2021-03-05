<?php


namespace SynerBay\Forms\Validators;


use SynerBay\Helper\IntegrityHelper;

class Required extends AbstractValidator
{
    public function run($value)
    {
        return !IntegrityHelper::isEmptyValue($value);
    }

    public function error(): string
    {
        return 'This field is required!';
    }
}