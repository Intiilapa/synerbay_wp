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

if ($currentDate > $time_remaining)
   echo apply_filters('woocommerce_groupbuy_bage', '<span class="groupbuy-bage dashicons dashicons-yes-alt"></span>', $product);
else {
  echo apply_filters('woocommerce_groupbuy_bage', '<span class="groupbuy-bage dashicons dashicons-groups"></span>', $product);
}
