<?php


namespace SynerBay\Forms\Validators;


class PriceStep extends AbstractValidator
{
    public function error(): string
    {
        return 'Invalid or empty value detected. Examples for allowed number formats: qty -> 100, price: 2.5 or 5';
    }

    protected function run($value)
    {
        $requiredValidator = new Required();
        $integerValidator = new Integer();
        $numericValidator = new Numeric();

        $ret = true;

        foreach ($value as $step) {
            if (!
                    (
                        $requiredValidator->run($step['qty']) &&
                        $requiredValidator->run($step['price']) &&
                        $numericValidator->run($step['qty']) &&
                        $numericValidator->run($step['price']) &&
                        $integerValidator->run($step['qty'])
                    )
            ) {
                $ret = false;
                break;
            }
        }

        return $ret;
    }
}