<?php

use SynerBay\Forms\CreateOffer;

    $get_data  = wp_unslash( $_GET ); // WPCS: CSRF ok.
    $post_data = wp_unslash( $_POST ); // WPCS: CSRF ok.

    $current_time = date( 'g:i A' );

    /**
     *  dokan_new_product_wrap_before hook
     *
     *  @since 2.4
     */
    do_action( 'dokan_new_product_wrap_before' );
?>

<?php do_action( 'dokan_dashboard_wrap_start' ); ?>

    <div class="dokan-dashboard-wrap">

        <?php

            /**
             *  dokan_dashboard_content_before hook
             *  dokan_before_new_product_content_area hook
             *
             *  @hooked get_dashboard_side_navigation
             *
             *  @since 2.4
             */
            do_action( 'dokan_dashboard_content_before' );
            do_action( 'dokan_before_new_product_content_area' );
        ?>


        <div class="dokan-dashboard-content">

            <?php

                /**
                 *  dokan_before_new_product_inside_content_area hook
                 *
                 *  @since 2.4
                 */
                do_action( 'dokan_before_new_product_inside_content_area' );
            ?>
            <header class="dokan-dashboard-header dokan-clearfix">
                <h1 class="entry-title">
                    <?php esc_html_e( 'Create new Offer', 'dokan-lite' ); ?>
                </h1>
                <div class="notice-box">
                    <span>
                        <b>IMPORTANT!</b></br>
                        Once you have created the offer, you can not delete later.</br>
                        When you set the expiration date of the offer, it will automatically close itself (you don’t have to delete it later)</br>
                        In case if any unexpected circumstance affects the ability to fulfill the order, you can later accept or decline your customer requests (so you can exempt yourself to fulfill any order).</br>
                        <b>Current timezone: </b>UTC+0 (Preview: <?php echo $current_time;?>) </br>
                    </span>
                </div>
            </header>

            <div class="dokan-new-product-area">
                <?php
                $error_messages = [];
                $created = false;
                if (isset($post_data['add_offer'])) {
                    $formData = $post_data;

                    if (isset($formData['price_steps'])) {
                        $formData['price_steps'] = json_decode($post_data['price_steps'], true);
                    }

                    /** @var CreateOffer $offerForm */
                    $offerForm = apply_filters('synerbay_get_offer_create_form', $formData);

                    if ($offerForm->validate()) {
                        $created = apply_filters('synerbay_create_offer', $offerForm->getFilteredValues());
                    } else {
                        $error_messages = $offerForm->errorMessages();
                    }
                }
                ?>
                <?php if ( $created ): ?>
                    <?php echo "<script>location.href='/dashboard/my-offers?operation=success';</script>"; ?>
                <?php endif ?>

                <?php

                $can_sell = apply_filters( 'dokan_can_post', true );

                if ( $can_sell ) {
                    if ( dokan_is_seller_enabled( get_current_user_id() ) ) { ?>


                        <form class="dokan-form-container" method="post">
                            <div class="product-edit-container dokan-clearfix">
                                <div class="content-half-part dokan-product-meta">
                                    <?php
                                        do_action('synerbay_getDokanMyProductsSelect', isset($post_data['post_id']) ? $post_data['post_id'] : false, $error_messages);
                                        do_action('synerbay_getDokanOfferDefaultPrice', isset($post_data['default_price']) ? $post_data['default_price'] : 0, $error_messages);
                                        do_action('synerbay_getPriceStepInput', isset($post_data['price_steps']) ? $post_data['price_steps'] : false, $error_messages);
                                        do_action('synerbay_getDokanOfferMinimumOrderQTYInput', isset($post_data['minimum_order_quantity']) ? $post_data['minimum_order_quantity'] : '', $error_messages);
                                        do_action('synerbay_getDokanOfferOrderQTYStepInput', isset($post_data['order_quantity_step']) ? $post_data['order_quantity_step'] : '', $error_messages);
                                        do_action('synerbay_getDokanOfferMaxTotalOfferQtyInput', isset($post_data['max_total_offer_qty']) ? $post_data['max_total_offer_qty'] : '', $error_messages);
                                        do_action('synerbay_getDokanParityTypesSelect', isset($post_data['transport_parity']) ? $post_data['transport_parity'] : false, $error_messages);
                                        do_action('synerbay_getDokanOfferStartDate', isset($post_data['offer_start_date']) ? $post_data['offer_start_date'] : '', $error_messages);
                                        do_action('synerbay_getDokanOfferEndDate', isset($post_data['offer_end_date']) ? $post_data['offer_end_date'] : '', $error_messages);
                                        do_action('synerbay_getDokanOfferDeliveryStartDate', isset($post_data['delivery_date']) ? $post_data['delivery_date'] : '', $error_messages);
                                        do_action('synerbay_getDokanShippingToOfferSelect', isset($post_data['shipping_to']) ? $post_data['shipping_to'] : '', $error_messages);
                                        do_action('synerbay_getDokanVisibleSelect', isset($post_data['visible']) ? $post_data['visible'] : '', $error_messages);
                                    ?>
                                </div>
                            </div>

                            <?php do_action( 'dokan_new_product_form' ); ?>
                            </br>
                            <div class="dokan-form-group dokan-left">
                                <button type="submit" name="add_offer" class="dokan-btn dokan-btn-default dokan-btn-theme" value="create_new"><?php esc_attr_e( 'Create offer', 'dokan-lite' ); ?></button>
                            </div>

                        </form>

                    <?php } else { ?>

                        <?php dokan_seller_not_enabled_notice(); ?>

                    <?php } ?>

                <?php } else { ?>

                    <?php do_action( 'dokan_can_post_notice' ); ?>

                <?php } ?>
            </div>

            <?php

                /**
                 *  dokan_after_new_product_inside_content_area hook
                 *
                 *  @since 2.4
                 */
                do_action( 'dokan_after_new_product_inside_content_area' );
            ?>

        </div> <!-- #primary .content-area -->

        <?php

            /**
             *  dokan_dashboard_content_after hook
             *  dokan_after_new_product_content_area hook
             *
             *  @since 2.4
             */
            do_action( 'dokan_dashboard_content_after' );
            do_action( 'dokan_after_new_product_content_area' );
        ?>

    </div><!-- .dokan-dashboard-wrap -->

<?php do_action( 'dokan_dashboard_wrap_end' ); ?>

<?php

    /**
     *  dokan_new_product_wrap_after hook
     *
     *  @since 2.4
     */
    do_action( 'dokan_new_product_wrap_after' );
?>

<?php
    // js
    do_action('synerbay_getPriceStepDokanJS');
?>
