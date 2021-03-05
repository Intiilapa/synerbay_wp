<?php


namespace SynerBay\Forms\Validators;


class ProductExists extends AbstractValidator
{
    public function run($value)
    {
        global $wpdb;

        $wheres = [
            'ID = ' . $value,
            'post_type = "product"',
        ];

        if (count($this->validatorParameters)) {
            foreach ($this->validatorParameters as $columnName => $columnValue)  {
                $wheres[] = $columnName . ' = ' . $columnValue;
            }
        }

        $result = $wpdb->get_results('select count(id) as count from sb_posts WHERE ' . implode(' and ', $wheres));

        return (bool)$result[0]->count;
    }

    public function error(): string
    {
        return 'Invalid product, please select valid product!';
    }
}