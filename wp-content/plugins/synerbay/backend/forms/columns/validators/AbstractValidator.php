<?php


namespace SynerBay\Forms\Validators;


use SynerBay\Helper\IntegrityHelper;

abstract class AbstractValidator
{
    protected array $validatorParameters;

    public function __construct(array $validatorParameters = [])
    {
        $this->validatorParameters = $validatorParameters;
    }

    public function validate($value)
    {
        if (!($this instanceof Required) && IntegrityHelper::isEmptyValue($value)) {
            return true;
        }

        return $this->run($value);
    }

    abstract public function error(): string;

    abstract protected function run($value);
}