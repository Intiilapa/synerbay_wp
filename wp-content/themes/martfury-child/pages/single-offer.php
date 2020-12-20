<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */
global $offer;
global $product;
$product = $offer['product']['wc_product'];
//print '<pre>';
//var_dump($product);
//die;

//print '<pre>';
//var_dump($offer);die;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

	<?php
echo  '<table><tr>'
	. '<td>'. $offer['id'] . '</td>'
	. '<td>'. $offer['product']['post_title'] . '</td>'
	. '<td>'. $offer['delivery_date'] . '</td>'
	. '<td>'. $offer['offer_start_date'] . '</td>'
	. '<td>'. $offer['offer_end_date'] . '</td>'
	. '<td>'. $offer['minimum_order_quantity'] . '</td>'
	. '<td>'. $offer['max_total_offer_qty'] . '</td>'
	. '<td>'. $offer['transport_parity'] . '</td>'
	. '<td>'. $offer['created_at'] . '</td>'
	. '<td>'. $offer['summary']['formatted_actual_product_price'] . '</td>'
	. '</tr></table>';
	?>

	<?php
		wc_get_template_part( 'content', 'single-offer' );
	?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php
get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
