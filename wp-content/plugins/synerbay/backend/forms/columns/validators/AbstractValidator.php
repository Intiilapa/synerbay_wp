<?php


namespace SynerBay\Forms\Validators;


use SynerBay\Helper\IntegrityHelper;

abstract class AbstractValidator
{
    protected array $validatorParameters;

    protected array $formValues = [];

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

    public function setFormValues(array $formValues = [])
    {
        $this->formValues = $formValues;

    }

    protected function getFormValue($key)
    {
        return array_key_exists($key, $this->formValues) && $this->formValues[$key] ? $this->formValues[$key] : false;
    }

    abstract public function error(): string;

    abstract protected function run($value);
}