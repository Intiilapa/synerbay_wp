<?php
/**
 * Group Buy deal badge template
 *
 * @author WooThemes
 * @package WooCommerce/Templates
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $offer;

$time_remaining = strtotime($offer['offer_end_date'])-  (get_option( 'gmt_offset' )*3600);
$time_for_start = strtotime($offer['offer_start_date'])-  (get_option( 'gmt_offset' )*3600);

$currentDate = strtotime(date('Y-m-d H:i:s'));

?>
<?php if($offer['summary']['actual_product_price'] != 0): ?>
    <p class="price">
        <?php echo $offer['summary']['formatted_actual_product_price'];?>
    </p>
<?php endif;?>

<?php if($currentDate > strtotime($offer['offer_start_date'])) : ?>

    <div class='groupbuy-ajax-change'>
        <div class="groupbuy-time" id="countdown"><?php echo apply_filters('time_text', __( 'Time left:', 'wc_groupbuy' ), $product); ?>
            <div class="main-groupbuy groupbuy-time-countdown" data-time="<?php echo $time_remaining ?>" data-groupbuyid="<?php echo $product->get_id() ?>" data-format="<?php echo get_option( 'simple_groupbuy_countdown_format' ) ?>"></div>
        </div>
    </div>

<?php elseif($currentDate < strtotime($offer['offer_start_date'])) : ?>

    <div class="groupbuy-time future" id="countdown"><?php echo  __( 'Offer starts in:', 'wc_groupbuy' ) ?>
        <div class="groupbuy-time-countdown future" data-time="<?php echo $time_for_start ?>" data-format="<?php echo get_option( 'simple_groupbuy_countdown_format' ) ?>"></div>
    </div>

<?php endif; ?>