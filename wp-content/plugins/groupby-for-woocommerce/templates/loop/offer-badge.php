<?php
/**
 * Group Buy badge template
 *
 * @author 	WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

?>

<?php echo apply_filters('woocommerce_groupbuy_bage', '<span class="groupbuy-bage dashicons dashicons-groups"></span>',  $product); ?>
