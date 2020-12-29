<?php


namespace SynerBay\Functions;


use SynerBay\Forms\CreateProduct;

class Dokan
{
    public function __construct()
    {
        add_action('dokan_new_product_added', [$this, 'productCreateHook'], 10, 2);
        add_action('dokan_product_updated', [$this, 'productEditHook'], 10, 2);
    }

    public function productCreateHook(int $product_id, array $postData)
    {
        $form = new CreateProduct($postData);
        $values = $form->getFilteredValues();

        add_post_meta( $product_id, '_weight_unit', $values['weight_unit']);
        add_post_meta( $product_id, '_weight_unit_type', $values['weight_unit_sign']);
        add_post_meta( $product_id, '_material', $values['material']);
    }

    public function productEditHook(int $product_id, $postData)
    {
        $form = new CreateProduct($postData);
        $values = $form->getFilteredValues();

        update_post_meta( $product_id, '_weight_unit', $values['weight_unit']);
        update_post_meta( $product_id, '_weight_unit_type', $values['weight_unit_sign']);
        update_post_meta( $product_id, '_material', $values['material']);
    }

}