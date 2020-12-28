<?php


namespace SynerBay\Forms\Validators;


class NumericGreaterThenColumn extends AbstractValidator
{
    private string $columnValue;

    public function error(): string
    {
        return sprintf('The value must be greater than %s!', $this->columnValue);
    }

    protected function run($value)
    {
        $ret = true;
        $columnValue = $this->getFormValue($this->validatorParameters['column']);

        if (!empty($value) && !empty($columnValue)) {
            $this->columnValue = $columnValue;
            $numericValidator = new Numeric();

            $ret = $numericValidator->run($value) && $numericValidator->run($columnValue) && $value > $columnValue;
        }

        return $ret;
    }
}