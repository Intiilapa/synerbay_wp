<?php


namespace SynerBay\Forms\Validators;


class StringLength extends AbstractValidator
{

    public function run($value)
    {
        $valid = true;

        if (array_key_exists('min', $this->validatorParameters) && mb_strlen($value) < $this->validatorParameters['min']) {
            $valid = false;
        }

        if (array_key_exists('max', $this->validatorParameters) && mb_strlen($value) > $this->validatorParameters['max']) {
            $valid = false;
        }

        return $valid;
    }

    public function error(): string
    {
        $parameters = [];

        if (array_key_exists('min', $this->validatorParameters)) {
            $parameters[] = 'min: ' . $this->validatorParameters['min'];
        }

        if (array_key_exists('max', $this->validatorParameters)) {
            $parameters[] = 'max: ' . $this->validatorParameters['max'];
        }

        return 'Invalid text length. ' . ucfirst(implode(', ', $parameters)) . '!';
    }
}