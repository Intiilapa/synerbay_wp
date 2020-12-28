<?php


namespace SynerBay\Forms\Validators;


class DateGreaterThenColumn extends AbstractValidator
{
    private string $columnValue;

    public function error(): string
    {
        return sprintf('Date must be greater than %s!', $this->columnValue);
    }

    protected function run($value)
    {
        $requiredValidator = new Required();
        $dateValidator = new Date();
        $columnValue = $this->getFormValue($this->validatorParameters['column']);

        $ret = $requiredValidator->run($columnValue) && $dateValidator->run($columnValue) && strtotime($columnValue) < strtotime($value);

        if (!$ret) {
            $this->columnValue = $columnValue;
        }

        return $ret;
    }
}