<?php


namespace SynerBay\Functions;


use SynerBay\Forms\CreateProduct;
use SynerBay\Functions\Dokan\Vendor\Wizard\SetupWizard;
use SynerBay\Helper\RouteHelper;
use SynerBay\Helper\SynerBayDataHelper;
use WC_Product;
use WP_Role;
use WP_User;

class Dokan
{
    public function __construct()
    {
        add_action('dokan_new_product_added', [$this, 'productCreateHook'], 10, 2);
        add_action('dokan_product_updated', [$this, 'productEditHook'], 10, 2);
        add_action('init', [$this, 'addShopUserRole'], 10, 2);

        // magic szám, nem piszkáld, csak így tudjuk behúzni a saját setup wizardunkat
        add_action( 'init', array( $this,'validateEmailLink' ), 99 );

        // login
//        add_action('wp_login', [$this, 'fixUserRole'], 10, 2);
        // shortcode add args
        add_filter( 'dokan_seller_listing_args', [ $this, 'storeArgs' ], 20, 2 );
    }

    /**
     * Shortcode + search
     *
     * @param $args
     * @param $request
     * @return mixed
     */
    public function storeArgs($args, $request)
    {
        $args['role__in'] = ['seller', 'administrator', 'synerbay_user'];
        return $args;
    }

    /**
     * A dokan pronak a kurva anyját :D
     * Ezt a hekket, csak azért nem tudjuk felülírni a defaultját, te jó ég!!!!!!
     * Az alatta lévő szart behúzza 100-as prioval és mi behekkeltük!!!
     *
     * @see \WeDevs\DokanPro\EmailVerification::validate_email_link
     *
     */
    public function validateEmailLink()
    {
        if ( !isset( $_GET['dokan_email_verification'] ) && empty( $_GET['dokan_email_verification'] ) ) {
            return;
        }

        if ( !isset( $_GET['id'] ) && empty( $_GET['id'] ) ) {
            return;
        }

        $user_id = intval( $_GET['id'] );
        $activation_key = $_GET['dokan_email_verification'];

        if ( get_user_meta( $user_id, '_dokan_email_verification_key', true ) != $activation_key ) {
            return;
        }

        delete_user_meta( $user_id, '_dokan_email_pending_verification' );
        delete_user_meta( $user_id, '_dokan_email_verification_key' );

        do_action( 'woocommerce_set_cart_cookies', true );

        $user = get_user_by( 'id', $user_id );

        if ( $user ) {
            clean_user_cache( $user_id );
            wp_clear_auth_cookie();
            wp_set_current_user( $user_id, $user->user_login );

            if ( is_ssl() == true ) {
                wp_set_auth_cookie( $user_id, true, true );
            } else {
                wp_set_auth_cookie( $user_id, true, false );
            }

            update_user_caches( $user );
        }

        $seller_wizard = new SetupWizard();
        $seller_wizard->setup_wizard();
    }

    public function productCreateHook(int $product_id, array $postData)
    {
        $form = new CreateProduct($postData);
        $values = $form->getFilteredValues();

        add_post_meta($product_id, '_weight_unit', $values['weight_unit']);
//        add_post_meta($product_id, '_weight_unit_type', $values['weight_unit_sign']);
        add_post_meta($product_id, '_material', $values['material']);

        $this->refreshProductCategories($product_id, $postData);
    }

    public function productEditHook(int $product_id, $postData)
    {
        $form = new CreateProduct($postData);
        $values = $form->getFilteredValues();

        update_post_meta($product_id, '_weight_unit', $values['weight_unit']);
//        update_post_meta($product_id, '_weight_unit_type', $values['weight_unit_sign']);
        update_post_meta($product_id, '_material', $values['material']);

        $this->refreshProductCategories($product_id, $postData);
    }

    /**
     * Ez a funkció felel, hogy rendbe tegye a product katokat DB szinten
     *
     * @param int $product_id
     * @param     $postData
     */
    private function refreshProductCategories(int $product_id, $postData)
    {
        if (array_key_exists('product_cat', $postData)) {
            $categories = SynerBayDataHelper::getProductCategoryIdsWithParentID();
            /** @var WC_Product $product */
            $product = wc_get_product($product_id);

            $prodCat = (int)$postData['product_cat'];
            $categoriesToSave = [$prodCat];

            if (array_key_exists($prodCat, $categories) && !empty($categories[$prodCat])) {
                $parentCat = $categories[$prodCat];

                $categoriesToSave[] = $parentCat;

                if (array_key_exists($parentCat, $categories) && !empty($categories[$parentCat])) {
                    $parentCat = $categories[$parentCat];

                    $categoriesToSave[] = $parentCat;
                }
            }

            $product->set_category_ids($categoriesToSave);
            $product->save();
        }
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
                'customer' => true,
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
//            // dokan megkötések
//            $vendor = dokan_get_vendor($user->ID);
//            $vendor->update_meta( 'dokan_enable_selling', 'yes' );
//            $vendor->update_meta( 'dokan_feature_seller', 'yes' );
//            $vendor->update_meta( 'dokan_publishing', 'yes' );
//            // role beállítása
//            $user->set_role('synerbay_user');
        }
    }

    public function dokan_store_tabs($tabs, $store_id)
    {
        $userdata         = get_userdata( $store_id );
        $user_nicename    = ( ! false == $userdata ) ? $userdata->user_nicename : '';

        $tabs['offers'] = [
            'title' => __( 'Offers', 'dokan-lite' ),
            'url'   => RouteHelper::generateRoute('vendor_offers_tab', ['store_name' => $user_nicename]),
        ];

        return $tabs;
    }
}