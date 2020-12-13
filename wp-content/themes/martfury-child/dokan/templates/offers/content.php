<?php
/**
 * Dokan Offer Content Template
 *
 * @since 2.4
 *
 * @package dokan
 */
?>

<div class="dokan-comments-wrap">
    <?php
        /**
         * dokan_review_content_status_filter hook
         *
         * @hooked dokan_review_status_filter
         */
            do_action('dokan_offer_filter');
            do_action( 'dokan_active_offer_table');
            do_action( 'dokan_my_offer_table');
        ?>
</div>