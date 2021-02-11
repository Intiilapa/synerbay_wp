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
global $post;
global $wp_query;

$product = wc_get_product($offer['product_id']);
$wp_query->post = $post = get_post($offer['product_id']);
$wp_query->is_singular = true;
$wp_query->is_single = true;
$wp_query->is_404 = false;
status_header( 200 );

/**
 * Edit offer tabs ...
 */
add_filter( 'woocommerce_product_tabs', 'wc_change_offers_tabs', 98 );

function wc_change_offers_tabs( $tabs ) {
	global $offer;
	/**
	 * add product data tab
	 */
//	$tabs['product_data_tab'] = array(
//		'title' 	=> __( 'Product Details', 'woocommerce' ),
//		'priority' 	=> 5,
//		'callback' 	=> function () use ($offer){
//			/** @var WC_Product $productWCProduct */
//			$productWCProduct = $offer['product']['wc_product'];
//			$productMetadata = $offer['product']['meta'];
//
//			$attributeSkeleton = '<strong>%s</strong>: %s<br>';
//			$desc = '';
//
//			if (!empty($productMetadata['_weight_unit']) && !empty($productMetadata['_weight_unit_type'])) {
//				$desc .= sprintf($attributeSkeleton, 'Unit / piece: ', $productMetadata['_weight_unit'] . $productMetadata['_weight_unit_type']);
//			}
//
//			if (!empty($productWCProduct->get_short_description())) {
//				$desc .= sprintf($attributeSkeleton, 'Short description: ', '<br>'. $productWCProduct->get_short_description());
//			}
//
//			if (!empty($desc)) {
//				echo $desc;
//			}
//
//			// VAR DUMP ...
//			print '<pre>';
//			var_dump($offer['product']);
//		},
//	);

	/**
	 * add shipping to for description
	 */
	$tabs['shipping']['callback'] = function () use ($offer){
		$desc = '<strong>Shipping to:</strong><br>' .
			$offer['shipping_to_labels'];

		echo $desc;
	};

	return $tabs;
}

// end editing

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
