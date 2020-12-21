<?php
/**
 * Group Buy deal product add to cart template
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $post;

// TODO Remco itt van példa a select és mult iselect generáláshoz használható synerbay mezők
// select genaret example (backend/hmlElemnts/SelectElement.php)
//do_action('synerbay_getUnitTypesSelect');
//do_action('synerbay_getUnitTypesSelect', 'cl');
// multiselect example
//do_action('synerbay_getMaterialTypesSelect');
//do_action('synerbay_getMaterialTypesSelect', ['wood', 'chemical']);
// my product select example
//do_action('synerbay_getMyProductsSelect');
//die;

// todo Remco test, a paraméter az offer id
//offer init example ...

//do_action('synerbay_init_global_offer_by_id', 20);
//global $offer;
//
//print '<pre>';
//var_dump($offer['current_price']);
//die;

// dashboard -> my offers
//do_action('synerbay_init_global_my_offers_for_dashboard');
//global $myOffers;
//print '<pre>';var_dump($myOffers);die;

// dashboard -> my offer applies
//do_action('synerbay_init_global_my_offer_applies_for_dashboard');
//global $myOfferApplies;
//
//foreach ($myOfferApplies as $offerApply) {
//    echo 'Offer id: ' . $offerApply['offer_id'] . ' - '
//        .'My apply qty: ' . $offerApply['qty'] .' - '
//        .'Actual product price: ' . $offerApply['offer']['summary']['formatted_actual_product_price'] . ' - '
//        .'Actual applicant product qty sum: ' . $offerApply['offer']['summary']['actual_applicant_product_number'] . ' - '
//        .'Product name: ' . $offerApply['offer']['product']['post_title'] . ' - '
//        . do_action('synerbay_disAppearOfferDashBoardButton', $offerApply['offer_id'])
//        .'<a href="stage.synerbay.com/offer/'.$offerApply['offer_id'].'">Ugrás az ajánlatra</a><br><hr/>';
//}
//print '<pre>';var_dump($myOfferApplies);die;

// todo Remco ez egy komplett offer létrehozás példa, fájl helye wp-content/plugins/synerbay/backend/functions/Test.php -> test function
//do_action('synerbay_test');die('done');


$id = $product->get_main_wpml_product_id();

if ( ! $product->is_purchasable() OR ! $product->is_in_stock() OR $product->is_closed() ) return;

?>
<?php //do_action('woocommerce_before_add_to_cart_form');?>

    <form class="buy-now cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>">
        <?php

//        do_action('woocommerce_before_add_to_cart_button');

        if ( ! $product->is_sold_individually() )
            woocommerce_quantity_input( array(
                'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
                'max_value' => apply_filters( 'woocommerce_quantity_input_max',  $product->get_max_purchase_quantity(), $product ),
                'step' => 5,
            ) );
        ?>

<!--        <div>-->
<!--            <input type="hidden" name="add-to-cart" value="--><?php //echo $product->get_id(); ?><!--" />-->
<!--            <input type="hidden" name="product_id" value="--><?php //echo esc_attr( $post->ID ); ?><!--" />-->
<!--        </div>-->
        <?php
        echo 'Metadata: ';
            $pageview = esc_attr( get_post_meta( $post->ID, 'pageview', true ) );
                echo '<p>' . __( 'pageview:') . $pageview . '</p>';
            ?>

        <?php do_action('synerbay_offerApplyButton', $product);?>
<!--        --><?php //do_action('woocommerce_after_add_to_cart_button'); ?>
    </form>

