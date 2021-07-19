<?php
/**
 * Group Buy badge template
 *
 * @author    WooThemes
 * @package    WooCommerce/Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

global $product, $offer;
$time_remaining = strtotime($offer['offer_end_date']) - (get_option('gmt_offset') * 3600);
$time_for_start = strtotime($offer['offer_start_date']) - (get_option('gmt_offset') * 3600);
$currentDate = strtotime(date('Y-m-d H:i:s'));

$hot_offer_badge = $offer['summary']['hot_offer'];
$last_minute_offer = $offer['summary']['last_minute_offer'];
?>

<div class="wrapper-icons">

<?php
if ($currentDate > $time_remaining)
   echo apply_filters('woocommerce_groupbuy_bage', '<div class="groupbuy-bage dashicons dashicons-yes-alt"></div>', $product);
else {
  echo apply_filters('woocommerce_groupbuy_bage', '<div class="groupbuy-bage dashicons dashicons-groups"></div>', $product);
}

if (hot_offer_badge == 1) {
    echo apply_filters('woocommerce_groupbuy_bage', '<div class="groupbuy-bage dashicons offer-hot"></div>', $product);
}

if ($last_minute_offer == 1) {
    echo apply_filters('woocommerce_groupbuy_bage', '<div class="groupbuy-bage dashicons offer-time"></div>', $product);
}?>

</div>
