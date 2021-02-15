<?php


namespace SynerBay\Forms\Validators;


use DateTime;

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
        $date1 = new DateTime($columnValue);
        $date2 = new DateTime($value);

        $ret = $requiredValidator->run($columnValue) && $dateValidator->run($columnValue) && $date1 < $date2;

        if (!$ret) {
            $this->columnValue = $columnValue;
        }

        return $ret;
    }
}