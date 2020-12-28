<?php


namespace SynerBay\Helper;


class IntegrityHelper
{
    public static function isEmptyValue($value): bool
    {
        return
            empty($value) ||
            is_null($value)  ||
            (is_string($value) && empty($value)) ||
            (is_array($value) && !count($value)) ||
            (is_numeric($value) && ctype_digit($value) && (int)$value === 0);
    }
}