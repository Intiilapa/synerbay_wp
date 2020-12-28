<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
global $offer;

/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form();

    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>


    <div class="mf-product-detail">
        <?php
        /**
         * woocommerce_before_single_product_summary hook.
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action( 'woocommerce_before_single_product_summary' );
        ?>
        <div class="summary entry-summary">
            <?php if($offer['summary']['actual_product_price'] != 0): ?>
                <p class="price">
                    <?php echo $offer['summary']['formatted_actual_product_price'];?>
                </p>
            <?php endif;?>

            <!-- Basic info -->
            <strong>Offer details:</strong>
            <p class="deal-info">
                <span class="min-deals"> <?php _e( 'To succeed:', 'synerbay' )?> <?php echo !empty($offer['summary']['max_price_step_qty']) ?  $offer['summary']['max_price_step_qty'] : '0' ;?></span>
                <span class="current_subscribed"> <?php _e( 'Amount subscribers:', 'synerbay' )?> <?php echo $offer['summary']['actual_applicant_product_number'];?></span>
                <span class="offer-start-date"> <?php _e( 'Start date:', 'synerbay' )?> <?php echo $offer['offer_start_date'];?></span>
                <span class="offer-end-date"> <?php _e( 'End date:', 'synerbay' )?> <?php echo $offer['offer_end_date'];?></span>
                <span class="offer-qty-min"> <?php _e( 'Min. product qty:', 'synerbay' )?> <?php echo $offer['minimum_order_quantity'];?></span>
                <span class="offer-qty-max"> <?php _e( 'Max. product qty/user:', 'synerbay' )?> <?php echo $offer['max_total_offer_qty'];?></span>
                <span class="offer-qty-step"> <?php _e( 'Quantity step:', 'synerbay' )?> <?php echo $offer['order_quantity_step'];?></span>
                <br>
                <span><?php _e( 'Transport parity:', 'synerbay' )?> <?php echo $offer['transport_parity'];?></span>
                <span><?php _e( 'Delivery date:', 'synerbay' )?> <?php echo $offer['delivery_date'];?></span>
            </p>

            <!-- Progress bar -->
            <strong>Progress:</strong>
            <div class="wcl-progress-meter">
                <span class="zero"><?php echo $offer['summary']['min_price_step_qty'] ?></span>
                <span class="max"><?php echo $offer['summary']['max_price_step_qty'] ?></span>
                <progress  max="<?php echo $offer['summary']['max_price_step_qty'] ?>" value="<?php echo !empty($offer['summary']['actual_applicant_product_number'
                ]) ? $offer['summary']['actual_applicant_product_number'] : '0' ?>"  low="<?php echo $offer['summary']['min_price_step_qty'] ?>"></progress>
            </div>

            <!-- Table -->
            <p class="deal-info">
                <strong>Price steps:</strong>
            <table width="100%">
                <thead>
                <tr>
                    <td>Quantity</td>
                    <td>Price</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($offer['price_steps'] as $price_step){
                    echo sprintf('<tr><td>%s+</td><td>%s</td></tr>', $price_step['qty'], wc_price($price_step['price']));
                }
                ?>
                </tbody>
            </table>

            <!-- Add to cart section -->
            <form class="buy-now cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $offer['product_id'] ?>">
                <?php
                if ($offer['summary']['show_quantity_input']) {
                    woocommerce_quantity_input( array(
                        'min_value' => apply_filters( 'woocommerce_quantity_input_min', isset($offer['minimum_order_quantity']) && $offer['minimum_order_quantity'] ? $offer['minimum_order_quantity'] : 1, $product ),
                        'max_value' => apply_filters( 'woocommerce_quantity_input_max', isset($offer['max_total_offer_qty']) && $offer['max_total_offer_qty'] ? $offer['max_total_offer_qty'] : -1, $product ),
                        'step' => isset($offer['order_quantity_step']) ? $offer['order_quantity_step'] : 1,
                    ) );
                }
                ?>

                <!-- Subscribe button -->
                <?php do_action('synerbay_offerApplyButton', $offer);?>
            </form>

        </div>
        <!-- .summary -->
    </div>
    <div class="summary-sep clear"></div>
    <div class="mf-product-summary">
        <?php
        /**
         * woocommerce_after_single_product_summary hook.
         *
         * @hooked woocommerce_output_product_data_tabs - 10
         * @hooked woocommerce_upsell_display - 15
         * @hooked woocommerce_output_related_products - 20
         */
        do_action( 'woocommerce_after_single_product_summary' );
        ?>
    </div>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
