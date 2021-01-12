<?php


namespace SynerBay\Forms\Validators;


class Integer extends AbstractValidator
{
    public function error(): string
    {
        return 'Invalid value! Please add a valid positive number. Example: 1, 10, 100, etc.';
    }

    protected function run($value)
    {
        $ret = true;
        $requiredValidator = new Required();

        if ($requiredValidator->run($value)) {
            $ret = ctype_digit($value) && $value > 0;
        }

        return $ret;
    }
}