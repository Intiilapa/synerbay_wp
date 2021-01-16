<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<aside id="primary-sidebar" class="widgets-area primary-sidebar col-md-3 col-sm-12 col-xs-12 <?php echo esc_attr( 'catalog-sidebar' ) ?>">
    <?php if ( is_active_sidebar( 'synerbay_sidebar' ) ) : ?>
        <?php dynamic_sidebar( 'synerbay_sidebar' ); ?>
    <?php endif; ?>
</aside><!-- #secondary -->

<div id="primary" class="content-area col-md-9 col-sm-12 col-xs-12 ?>">
