<?php
namespace SynerBay\Forms\Columns;


use SynerBay\Forms\Filters\AbstractFilter;
use SynerBay\Forms\Validators\AbstractValidator;

class Column
{
    public string $name;
    private array $validators = [];
    private array $filters = [];
    private bool $valid = true;
    private bool $required = false;
    public string $errorMessage;
    private $value;
    protected array $formValues = [];

    public const TEXT = 'text';
    public const SELECT = 'select';
    public const DATE = 'date';
    public const DATETIME = 'datetime';
    public const ARRAY = 'array';

    public function __construct(string $name, bool $required = false, array $validators = [], array $filters = [])
    {
        $this->name = $name;
        $this->required = $required;

        if ($this->required) {
            $this->addValidator('required');
        }

        $this->addValidators($validators);
        $this->addFilters($filters);
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setFormValues(array $formValues = [])
    {
        $this->formValues = $formValues;

        if (count($this->validators)) {
            /** @var AbstractValidator $validator */
            foreach ($this->validators as $validator) {
                $validator->setFormValues($formValues);
            }
        }
    }

    public function value()
    {
        return $this->value;
    }

    public function filteredValue()
    {
        return $this->filter($this->value);
    }

    public function addValidators(array $validators)
    {
        foreach($validators as $validatorName => $validatorParameters) {
            $this->addValidator($validatorName, $validatorParameters);
        }
    }

    public function addValidator($validatorName, array $params = [])
    {
        $validator = 'SynerBay\Forms\Validators\\' . ucfirst($validatorName);
        /** @var AbstractValidator $validatorObject */
        $validatorObject = new $validator($params);
        $validatorObject->setFormValues($this->formValues);
        $this->validators[$validatorName] = $validatorObject;
    }

    public function addFilters(array $filters)
    {
        foreach($filters as $filterName) {
            $this->addFilter($filterName);
        }
    }

    public function addFilter($filterName)
    {
        $filter = 'SynerBay\Forms\Filters\\' . ucfirst($filterName);

        $this->filters[$filterName] = new $filter();
    }

    public function filter($value)
    {
        if (count($this->filters)) {
            /** @var AbstractFilter $filter */
            foreach ($this->filters as $filter) {
                $value = $filter->getFilteredValue($value);
            }
        }

        return $value;
    }

    public function validate()
    {
        $this->valid = true;

        if (count($this->validators)) {
            /** @var AbstractValidator $validator */
            foreach($this->validators as $validator) {
                if (!$validator->validate($this->value)) {
                    $this->valid = false;
                    $this->errorMessage = $validator->error();
                    break;
                }
            }
        }

        return $this->valid;
    }
}