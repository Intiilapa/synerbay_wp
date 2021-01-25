<?php


namespace SynerBay\Functions;


use SynerBay\Forms\CreateProduct;
use WP_Role;
use WP_User;

class Dokan
{
    public function __construct()
    {
        add_action('dokan_new_product_added', [$this, 'productCreateHook'], 10, 2);
        add_action('dokan_product_updated', [$this, 'productEditHook'], 10, 2);
        add_action('init', [$this, 'addShopUserRole'], 10, 2);

        // login
        add_action('wp_login', [$this, 'fixUserRole'], 10, 2);
    }

    public function productCreateHook(int $product_id, array $postData)
    {
        $form = new CreateProduct($postData);
        $values = $form->getFilteredValues();

        add_post_meta($product_id, '_weight_unit', $values['weight_unit']);
        add_post_meta($product_id, '_weight_unit_type', $values['weight_unit_sign']);
        add_post_meta($product_id, '_material', $values['material']);
    }

    public function productEditHook(int $product_id, $postData)
    {
        $form = new CreateProduct($postData);
        $values = $form->getFilteredValues();

        update_post_meta($product_id, '_weight_unit', $values['weight_unit']);
        update_post_meta($product_id, '_weight_unit_type', $values['weight_unit_sign']);
        update_post_meta($product_id, '_material', $values['material']);
    }

    public function addShopUserRole()
    {
        /** @var WP_Role $sellerRole */
        $sellerRole = get_role('seller');
        $customerRole = get_role('customer');

        add_role('synerbay_user', 'SynerBay User');

        // Add caps for SynerBay User role.
        $role = get_role('synerbay_user');

        $capabilities = array_merge(
            [
                'seller'   => true,
//                'customer' => true,
                'dokanadr' => true,
            ],
            $sellerRole->capabilities,
            $customerRole->capabilities
        );

        foreach ($capabilities as $capability => $capabilityGrant) {
            if ($capability == 'customer') {
                continue;
            }
            $role->add_cap($capability, $capabilityGrant);
        }
    }

    /**
     * @param          $userLogin
     * @param WP_User $user
     */
    public function fixUserRole($userLogin, $user)
    {
        if (count($user->roles) == 1 && in_array($user->roles[0], ['seller', 'customer'])) {
            // dokan megkötések
            $vendor = dokan_get_vendor($user->ID);
            $vendor->update_meta( 'dokan_enable_selling', 'yes' );
            $vendor->update_meta( 'dokan_feature_seller', 'yes' );
            $vendor->update_meta( 'dokan_publishing', 'yes' );
            // role beállítása
            $user->set_role('synerbay_user');
        }
    }

}