<?php

namespace SynerBay\Forms;


use SynerBay\Forms\Columns\Column;

abstract class AbstractForm
{
    public array $formValues = [];

    private array $columns = [];

    private array $errorMessages = [];

    public function __construct(array $formValues = [])
    {
        $this->formValues = $formValues;

        $this->init();
        $this->setValues();
    }

    public function getValues()
    {
        return $this->formValues;
    }

    public function getFilteredValues()
    {
        $values = [];

        /** @var Column $column */
        foreach ($this->columns as $column) {
            $values[$column->name] = $column->filteredValue();
        }

        return $values;
    }

    protected function addColumn(Column $column)
    {
        $this->columns[] = $column;
    }

    private function setValues()
    {
        if (count($this->formValues)) {
            $availableFormValues = array_keys($this->formValues);
            /** @var Column $column */
            foreach ($this->columns as $column) {
                if (in_array($column->name, $availableFormValues)) {
                    $column->setValue($this->formValues[$column->name]);
                    $column->setFormValues($this->formValues);
                }
            }
        }
    }

    public function validate()
    {
        $valid = true;

        /** @var Column $column */
        foreach ($this->columns as $column) {
            if (!$column->validate()) {
                $valid = false;
                $this->errorMessages[$column->name] = $column->errorMessage;
            }
        }

        return $valid;
    }

    public function errorMessages()
    {
        return $this->errorMessages;
    }

    abstract protected function init();
}