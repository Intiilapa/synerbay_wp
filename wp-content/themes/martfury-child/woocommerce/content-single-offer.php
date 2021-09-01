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

use SynerBay\Model\Offer;
use SynerBay\Repository\OfferRepository;

defined('ABSPATH') || exit;

global $product;
global $offer;

(new OfferRepository())->increaseNumberOfViews($offer['id'], $offer['number_of_views']);

$minimum_order_quantity = $offer['minimum_order_quantity'] ? $offer['minimum_order_quantity'] : 1;
$max_total_offer_qty = $offer['max_total_offer_qty'] ? $offer['max_total_offer_qty'] : -1;
$order_quantity_step = $offer['order_quantity_step'] ? $offer['order_quantity_step'] : 1;

$offer['minimum_order_quantity'] = $offer['minimum_order_quantity'] ? $offer['minimum_order_quantity'] : 1;
$offer['max_total_offer_qty'] = $offer['max_total_offer_qty'] ? $offer['max_total_offer_qty'] : '-';
$offer['order_quantity_step'] = $offer['order_quantity_step'] ? $offer['order_quantity_step'] : 1;

$time_remaining = strtotime($offer['offer_end_date']) - (get_option('gmt_offset') * 3600);
$time_for_start = strtotime($offer['offer_start_date']) - (get_option('gmt_offset') * 3600);

$currentDate = strtotime(date('Y-m-d H:i:s'));
$offer_validity = date('Y-m-d', strtotime($offer['offer_end_date']));

/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();

    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>


    <div class="mf-product-detail">
        <?php
        /**
         * woocommerce_before_single_product_summary hook.
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action('woocommerce_before_single_product_summary');
        ?>

        <div class="summary entry-summary">
            <?php if ($offer['summary']['actual_product_price'] != 0): ?>
                <p class="price">

                    <?php echo '<b style="color: green;font-size: 25px;"> ' . $offer['summary']['formatted_actual_product_price'] . '</b> ';

                        if ( $offer['summary']['formatted_actual_default_product_price'] != $offer['summary']['formatted_actual_product_price']) {
                            echo ' <b style="text-decoration: line-through;color:grey;font-weight: 400;font-size: 14px;">' . $offer['summary']['formatted_actual_default_product_price'] . '</b>';
                        }
                        if ($offer['summary']['current_discount_percentage_from_default_price'] != 0) {
                            echo ' <b style="color: red;font-weight: 400;font-size: 14px">' . '(-' . $offer['summary']['current_discount_percentage_from_default_price'] . '%)' . '</b>';
                        }
                    ?>

                </p>
                <hr>

                <?php echo '<div class="mb-1"><b style="font-weight: 400;color:red;">' . 'Offer validity: ' . $offer_validity . '</b></div>'; ?>
            <?php endif; ?>

            </br>
            <!-- Basic info -->
            <strong class="details-title">Offer details:</strong>
            <ul class="offer-list">
                <li><span class="current_subscribed"> <?php _e('Active amount subscribers: ',
                            'synerbay') ?><?php echo $offer['summary']['actual_applicant_product_number']; ?></span>
                </li>
                <li><span class="offer-start-date"> <?php _e('Start date: ',
                            'synerbay') ?><?php echo $offer['offer_start_date']; ?></span></li>
                <li><span class="offer-end-date"> <?php _e('End date: ',
                            'synerbay') ?><?php echo $offer['offer_end_date']; ?></span></li>
                <li><span class="offer-qty-min"> <?php _e('Unit: ',
                            'synerbay') ?><?php echo $offer['product']['meta']['_weight_unit']; ?></span></li>
                <li><span class="offer-qty-min"> <?php _e('Material: ',
                            'synerbay') ?><?php echo $offer['product']['meta']['_material']; ?></span></li>
                <li><span class="offer-qty-min"> <?php _e('Minimum Order Quantity: ',
                            'synerbay') ?><?php echo $offer['minimum_order_quantity']; ?></span></li>
                <li><span class="offer-qty-max"> <?php _e('Max. product qty/user: ',
                            'synerbay') ?><?php echo $offer['max_total_offer_qty']; ?></span></li>
                <li><span class="offer-qty-step"> <?php _e('Quantity step: ',
                            'synerbay') ?><?php echo $offer['order_quantity_step']; ?></span></li>
                <li><span><?php _e('Transport parity: ', 'synerbay') ?><?php echo $offer['transport_parity']; ?></span>
                </li>
                <li><span><?php _e('Delivery date: ', 'synerbay') ?><?php echo $offer['delivery_date']; ?></span></li>
                <li><span><?php _e('Payment term: ', 'synerbay') ?><?php echo $offer['payment_term']; ?></span></li>
                <li><span><?php _e('Viewers: ', 'synerbay') ?><?php echo $offer['number_of_views']; ?></span></li>
                <li>
                    <span><?php _e('Required order for further discount: ', 'synerbay') ?><?php echo $offer['summary']['next_price_step_required_qty'] <= 0 ? '-' : $offer['summary']['next_price_step_required_qty']; ?></span>
                </li>
            </ul>

            <!-- Table -->
            <p class="deal-info">
                <strong class="details-title">Price steps:</strong>
            <table width="100%">
                <thead>
                <tr>
                    <td>Quantity (<?php echo $offer['product']['meta']['_weight_unit']; ?> / Unit)</td>
                    <td>Price</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($offer['price_steps'] as $price_step) {
                    $trClass = '';
                    $tdClass = '';

                    if ($offer['summary']['actual_price_step_qty'] == $price_step['qty']) {
                        $trClass = ' class="step-green"';
                        $tdClass = ' class="step-green"';
                    }

                    echo sprintf('<tr%s><td%s>%s+</td><td%s>%s</td></tr>', $trClass, $tdClass, $price_step['qty'], $tdClass,
                        wc_price($price_step['price'], ['currency' => strtoupper($offer['currency'])]));
                }
                ?>
                </tbody>
            </table>
            <?php
                if ($offer['summary']['actual_applicant_product_number'] != 0){
                    echo '<p style="font-weight: 400;color:red;">Sold: ' .$offer['summary']['actual_applicant_product_number'] . '</p>';
                }
            ?>


            <!-- Add to cart section -->
            <form class="buy-now cart" method="post" enctype='multipart/form-data'
                  data-product_id="<?php echo $offer['product_id'] ?>">
                <?php
                if (!isset($classes) || empty($classes)) {
                    $classes[] = 'input-text qty text';
                }
                if ($offer['status'] != Offer::STATUS_CLOSED && !$offer['summary']['current_user_have_apply'] && !$offer['summary']['my_offer']) {
                    ?>

                    <div class="quantity">
                        <label class="screen-reader-text"
                               for="<?php echo esc_attr($offer['product_id']); ?>"><?php echo esc_html($label); ?></label>
                        <label class="label"
                               for="<?php echo esc_attr($offer['product_id']); ?>"><?php esc_html_e('Quantity', 'martfury'); ?></label>
                        <div class="qty-box">
                            <span class="decrease  icon_minus-06"></span>
                            <input
                                    type="number"
                                    id="<?php echo esc_attr($offer['product_id']); ?>"
                                    class="<?php echo esc_attr(join(' ', (array)$classes)); ?>"
                                    step="<?php echo esc_attr($order_quantity_step) ?>"
                                    min="<?php echo esc_attr($minimum_order_quantity); ?>"
                                    max="<?php echo esc_attr(0 < $max_total_offer_qty ? $max_total_offer_qty : ''); ?>"
                                    name="quantity"
                                    value="<?php echo esc_attr($minimum_order_quantity); ?>"
                                    title="<?php echo esc_attr_x('Qty', 'Product quantity input tooltip', 'martfury'); ?>"
                                    size="4"
                                    inputmode="numberic"
                                    readonly="readonly"/>
                            <?php do_action('woocommerce_after_quantity_input_field'); ?>
                            <span class="increase icon_plus"></span>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <!-- Subscribe button -->
                <?php do_action('synerbay_offerApplyButton', $offer); ?>
                <?php do_action('synerbay_offerInviteButton', $offer['url']); ?>
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
        do_action('woocommerce_after_single_product_summary');
        ?>
    </div>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action('woocommerce_after_single_product'); ?>
