<?php
/**
 *  Dokan Dashboard Template
 *
 *  Dokan Main Dahsboard template for Front-end
 *
 *  @since 2.4
 *
 *  @package dokan
 */
?>
<div class="dokan-dashboard-wrap">
    <?php do_action( 'dokan_dashboard_content_before' ); ?>
    <div class="dokan-dashboard-content">

        <?php
            do_action('dokan_offer_header');
            do_action('dokan_offer_content_inside_before' );
            //do_action('dokan_offer_filter');
        ?>

        <!-- dashboard-content-area -->
        <article class="offer-content-area">
        <?php do_action('dokan_main_content'); ?>
        </article>
        <!-- .dashboard-content-area -->

         <?php do_action( 'dokan_dashboard_content_inside_after' );?>
    </div><!-- .dokan-dashboard-content -->

    <?php do_action( 'dokan_dashboard_content_after' ); ?>

</div><!-- .dokan-dashboard-wrap -->