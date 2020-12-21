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
global $post;
$layout = 1;

//print '<pre>';
//var_dump($offer);
//die();

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
<div id="product-<?php the_ID(); ?>" class="mf-single-product mf-product-layout-1 product type-product">

	<div class="mf-product-detail">
		<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */

//        echo '<table><tr>'
//            . '<td>' . $offer['id'] . '</td>'
//            . '<td>' . $offer['product']['post_title'] . '</td>'
//            . '<td>' . $offer['delivery_date'] . '</td>'
//            . '<td>' . $offer['offer_start_date'] . '</td>'
//            . '<td>' . $offer['offer_end_date'] . '</td>'
//            . '<td>' . $offer['minimum_order_quantity'] . '</td>'
//            . '<td>' . $offer['max_total_offer_qty'] . '</td>'
//            . '<td>' . $offer['transport_parity'] . '</td>'
//            . '<td>' . $offer['created_at'] . '</td>'
//            . '<td>' . $offer['summary']['formatted_actual_product_price'] . '</td>'
//            . '<td>' . $offer['summary']['actual_applicant_product_number'] . '</td>'
//            . '<td>' . $offer['summary']['actual_applicant_number'] . '</td>'
//            . '<td>' . $offer['summary']['formatted_actual_product_price'] . '</td>'
//            . '</tr></table>';

        //do_action( 'woocommerce_before_single_product_summary' );

		?>

        <div class="mf-entry-product-header">
            <div class="mf-product-detail">
                <div class="entry-left">
                    <h1 class="product_title entry-title"><?php echo $offer['product']['post_title']?></h1>
                </div>
            </div>
        </div>

		<div class="summary entry-summary">
            <p class="price">
                <?php echo $offer['summary']['formatted_actual_product_price'];?>
            </p>

			<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			    //do_action( 'woocommerce_single_product_summary' );
                do_action('woocommerce_single_offer_price');

                //do_action('woocommerce_single_offer_header');
                //do_action('woocommerce_single_offer_entry_header');

            ?>
            <?php
            echo '<table><tr>'
                        . '<td>' . $offer['id'] . '</td>'
                        . '<td>' . $offer['product']['post_title'] . '</td>'
                        . '<td>' . $offer['delivery_date'] . '</td>'
                        . '<td>' . $offer['offer_start_date'] . '</td>'
                        . '<td>' . $offer['offer_end_date'] . '</td>'
                        . '<td>' . $offer['minimum_order_quantity'] . '</td>'
                        . '<td>' . $offer['max_total_offer_qty'] . '</td>'
                        . '<td>' . $offer['transport_parity'] . '</td>'
                        . '<td>' . $offer['created_at'] . '</td>'
                        . '<td>' . $offer['summary']['formatted_actual_product_price'] . '</td>'
                        . '<td>' . $offer['summary']['actual_applicant_product_number'] . '</td>'
                        . '<td>' . $offer['summary']['actual_applicant_number'] . '</td>'
                        . '<td>' . $offer['summary']['formatted_actual_product_price'] . '</td>'
                        . '</tr></table>';
            ?>

            <div class="groupbuy-time" id="countdown"><?php echo apply_filters('time_text', __( 'Time left:', 'wc_groupbuy' ), $product); ?>
                <div class="main-groupbuy groupbuy-time-countdown" data-time="<?php echo $product->get_seconds_remaining() ?>" data-groupbuyid="<?php echo $product->get_id() ?>" data-format="<?php echo get_option( 'simple_groupbuy_countdown_format' ) ?>"></div>
            </div>

            <div class='groupbuy-ajax-change'>

                <p class="deal-info">
                    <span class="min-deals"> <?php _e( 'Minimum:', 'synerbay' )?> <?php echo !empty($offer['minimum_order_quantity']) ?  $offer['minimum_order_quantity'] : '0' ;?></span>
                    <span class="current-sold"> <?php _e( 'Deals sold:', 'synerbay' )?> <?php echo !empty($offer['summary']['actual_applicant_product_number']) ?  $offer['summary']['actual_applicant_product_number'] : '0' ;?></span>
<!--                    <span class="shipping_to"> --><?php //_e( 'Shipping:', 'synerbay' )?><!-- --><?php //echo implode(', ', $offer['shipping_to']); ;?><!--</span>-->
                    <span class="shipping_to"> <?php _e( 'Shipping:', 'synerbay' )?> <?php echo implode(', ', $offer['shipping_to']); ;?></span>
                </p>
                <p class="deal-info">
                    <strong>Price steps:</strong>
                    <table width="100%">
                    <thead>
                    <tr><td>Offer current quantity</td><td>Product current price</td></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($offer['price_steps'] as $price_step){
                            echo sprintf('<tr><td>%s+</td><td>%s</td></tr>', $price_step['qty'], wc_price($price_step['price']));
                        }
                        ?>
                    </tbody>
                    </table>
                </p>
                <!-- Progress bar -->
                <div class="wcl-progress-meter">
                    <span class="zero">0</span>
                    <span class="max"><?php echo $offer['minimum_order_quantity'] ?></span>
                    <progress  max="<?php echo $offer['minimum_order_quantity'] ?>" value="<?php echo !empty($offer['summary']['actual_applicant_product_number'
                    ]) ? $offer['summary']['actual_applicant_product_number'] : '0' ?>"  low="<?php echo $offer['minimum_order_quantity'] ?>"></progress>
                </div>

            </div>

        <form class="buy-now cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $offer['product_id'] ?>">
            <?php
                if ( ! $product->is_sold_individually() )
                    woocommerce_quantity_input( array(
                        'min_value' => apply_filters( 'woocommerce_quantity_input_min', $offer['minimum_order_quantity'], $product ),
                        'max_value' => apply_filters( 'woocommerce_quantity_input_max', $offer['minimum_order_quantity'], $product ),
                        'step' => $offer['order_quantity_step'],
                    ) ); ?>
                <!-- Subscribe button -->
            <?php do_action('synerbay_offerApplyButton', $product);?>
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
		//do_action( 'woocommerce_after_single_product_summary' );
		?>
	</div>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
