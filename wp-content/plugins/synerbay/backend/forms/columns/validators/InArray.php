<?php


namespace SynerBay\Forms\Validators;

use Exception;

class InArray extends AbstractValidator
{
    public function run($values)
    {
        $valid = true;

        if (!array_key_exists('haystack', $this->validatorParameters)) {
            throw new Exception('Missing parameter haystack in validator!');
        }

        if (!is_array($values)) {
            $values = [$values];
        }

        foreach ($values as $value) {
            if (!in_array($value, $this->validatorParameters['haystack'])) {
                $valid = false;
                break;
            }
        }

        return $valid;
    }

    public function error(): string
    {
       return 'Invalid data, please select correct value!';
    }
}