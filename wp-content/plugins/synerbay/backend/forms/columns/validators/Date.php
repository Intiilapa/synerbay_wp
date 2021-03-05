<?php


namespace SynerBay\Forms\Validators;

use DateTime;

class Date extends AbstractValidator
{
    public function error(): string
    {
        return 'Invalid date!';
    }

    protected function run($value)
    {
        $formats = array("d.m.Y", "d/m/Y", "d-m-Y", "Y-m-d");

        $ret = false;

        foreach ($formats as $format) {
            if (DateTime::createFromFormat($format, $value)) {
                $ret = true;
                break;
            }
        }

        return $ret;
    }
}