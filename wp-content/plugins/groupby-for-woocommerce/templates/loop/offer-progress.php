<?php
/**
 * groupbuy badge template
 *
 * @author 	WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $offer;

$minimum_order_quantity = $offer['minimum_order_quantity'] ? $offer['minimum_order_quantity'] : 1;
$max_total_offer_qty = $offer['max_total_offer_qty'] ? $offer['max_total_offer_qty'] : -1;
$order_quantity_step = $offer['order_quantity_step'] ? $offer['order_quantity_step'] :1;

$offer['minimum_order_quantity'] = $offer['minimum_order_quantity'] ? $offer['minimum_order_quantity'] : 1;
$offer['max_total_offer_qty'] = $offer['max_total_offer_qty'] ? $offer['max_total_offer_qty'] : '-';
$offer['order_quantity_step'] = $offer['order_quantity_step'] ? $offer['order_quantity_step'] : 1;
?>

<?php if ( method_exists( $product, 'get_type') && $product->get_type() == 'groupbuy' ) : ?>

	<div class="wcl-progress-meter progresbar-<?php echo $product->get_id(); ?> <?php if($product->is_groupbuy_max_deals_met()) {echo 'full';} if(!$product->is_groupbuy_min_deals_met()) {echo 'no-min';} ?>">
        <span class="zero"><?php echo $offer['summary']['min_price_step_qty'] ?></span>
        <span class="max"><?php echo $offer['summary']['max_price_step_qty'] ?></span>
        <progress  max="<?php echo $offer['summary']['max_price_step_qty'] ?>" value="<?php echo !empty($offer['summary']['actual_applicant_product_number'
        ]) ? $offer['summary']['actual_applicant_product_number'] : '0' ?>"  low="<?php echo $offer['summary']['min_price_step_qty'] ?>"></progress>
	</div>
<?php endif;