<?php
/**
 *  Dokan Dashboard Template
 *
 *  Dokan Main Dahsboard template for Fron-end
 *
 *  @since 2.4
 *
 *  @package dokan
 */
?>
<div class="dokan-dashboard-wrap">
    <?php

        /**
         *  dokan_dashboard_content_before hook
         *
         *  @hooked get_dashboard_side_navigation
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_before' );
    ?>

    <div class="dokan-dashboard-content">

        <?php

            /**
             *  dokan_dashboard_content_before hook
             *
             *  @hooked show_seller_dashboard_notice
             *
             *  @since 2.4
             */
            do_action( 'dokan_offer_content_inside_before' );
        ?>

        <article class="offer-content-area">
        <h1> Add Your Content</h1>
          <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates maiores exercitationem ducimus totam alias. Voluptatum maxime laboriosam, quis quos eveniet architecto. Quia quam ab molestiae praesentium explicabo minus, deleniti obcaecati.	</p>

        </article><!-- .dashboard-content-area -->

         <?php

            /**
             *  dokan_dashboard_content_inside_after hook
             *
             *  @since 2.4
             */
            echo'e';

            //do_action('dokan_content_offer_filter');
            do_action( 'dokan_dashboard_content_inside_after' );
        ?>


    </div><!-- .dokan-dashboard-content -->

    <?php

        /**
         *  dokan_dashboard_content_after hook
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_after' );
    ?>

</div><!-- .dokan-dashboard-wrap -->