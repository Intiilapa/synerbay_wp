<?php

use SynerBay\Forms\UpdateOffer;

global $offer;
$get_data  = wp_unslash( $_GET ); // WPCS: CSRF ok.
$post_data = wp_unslash( $_POST ); // WPCS: CSRF ok.

if (!count($post_data)) {
    $post_data = $offer;
    $post_data['delivery_date'] = date('Y-m-d', strtotime($post_data['delivery_date']));
    $post_data['offer_start_date'] = date('Y-m-d', strtotime($post_data['offer_start_date']));
    $post_data['offer_end_date'] = date('Y-m-d', strtotime($post_data['offer_end_date']));
    $post_data['offer_id'] = $post_data['id'];
}

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
                <?php esc_html_e( 'Update Offer', 'dokan-lite' ); ?>
            </h1>
        </header><!-- .entry-header -->


        <div class="dokan-new-product-area">
            <!--check if create-->
            <?php
            $error_messages = [];
            $updated = false;
            if (isset($post_data['update_offer']) && $post_data['update_offer'] == 'update') {
                $formData = $post_data;

                if (isset($formData['price_steps'])) {
                    $formData['price_steps'] = json_decode($post_data['price_steps'], true);
                }

                /** @var UpdateOffer $offerForm */
                $offerForm = apply_filters('synerbay_get_offer_update_form', $formData);

                if ($offerForm->validate()) {
                    $updated = apply_filters('synerbay_update_offer', $offerForm->getFilteredValues());
                } else {
                    $error_messages = $offerForm->errorMessages();
                }
            }
            ?>

            <!--                --><?php //if ( count($error_messages) ) { ?>
            <!--                    <div class="dokan-alert dokan-alert-danger">-->
            <!--                        <a class="dokan-close" data-dismiss="alert">&times;</a>-->
            <!---->
            <!--                        --><?php //foreach ( $error_messages as $key => $error) { ?>
            <!---->
            <!--                            <strong>--><?php //esc_html_e( 'Error!', 'dokan-lite' ); ?><!--</strong> --><?php //echo $key . ' - ' . $error; ?><!--.<br>-->
            <!---->
            <!--                        --><?php //} ?>
            <!--                    </div>-->
            <!--                --><?php //} ?>

            <?php if ( $updated ): ?>
                <?php
                    echo "<script>location.href='/dashboard/my-offers?operation=success';</script>";
                ?>
            <?php endif ?>

            <?php

            $can_sell = apply_filters( 'dokan_can_post', true );

            if ( $can_sell ) {
                if ( dokan_is_seller_enabled( get_current_user_id() ) ) { ?>

                    <form class="dokan-form-container" method="post">

                        <div class="product-edit-container dokan-clearfix">
                            <div class="content-half-part dokan-product-meta">

                                <?php
                                //form elements ...
                                do_action('synerbay_getDokanOfferIDHiddenInput', isset($post_data['offer_id']) ? $post_data['offer_id'] : false, $error_messages);
                                do_action('synerbay_getDokanMyProductsSelect', isset($post_data['product_id']) ? $post_data['product_id'] : false, $error_messages);
                                do_action('synerbay_getPriceStepInput', isset($post_data['price_steps']) ? $post_data['price_steps'] : false, $error_messages);
                                do_action('synerbay_getDokanOfferUnitInput', isset($post_data['weight_unit']) ? $post_data['weight_unit'] : '', $error_messages);
                                do_action('synerbay_getDokanUnitTypesSelect', isset($post_data['unit_type']) ? $post_data['unit_type'] : false, $error_messages);
                                do_action('synerbay_getDokanOfferMinimumOrderQTYInput', isset($post_data['minimum_order_quantity']) ? $post_data['minimum_order_quantity'] : '', $error_messages);
                                do_action('synerbay_getDokanOfferOrderQTYStepInput', isset($post_data['order_quantity_step']) ? $post_data['order_quantity_step'] : '', $error_messages);
                                do_action('synerbay_getDokanOfferMaxTotalOfferQtyInput', isset($post_data['max_total_offer_qty']) ? $post_data['max_total_offer_qty'] : '', $error_messages);
                                do_action('synerbay_getDokanMaterialTypesSelect', isset($post_data['material']) ? $post_data['material'] : [], $error_messages);
                                do_action('synerbay_getDokanParityTypesSelect', isset($post_data['transport_parity']) ? $post_data['transport_parity'] : false, $error_messages);
                                do_action('synerbay_getDokanOfferStartDate', isset($post_data['offer_start_date']) ? $post_data['offer_start_date'] : '', $error_messages);
                                do_action('synerbay_getDokanOfferEndDate', isset($post_data['offer_end_date']) ? $post_data['offer_end_date'] : '', $error_messages);
                                do_action('synerbay_getDokanOfferDeliveryStartDate', isset($post_data['delivery_date']) ? $post_data['delivery_date'] : '', $error_messages);
                                do_action('synerbay_getDokanShippingToOfferSelect', isset($post_data['shipping_to']) ? $post_data['shipping_to'] : '', $error_messages);
                                ?>
                            </div>
                        </div>

                        <?php do_action( 'dokan_new_product_form' ); ?>

                        <hr>

                        <div class="dokan-form-group dokan-left">
                            <button type="submit" name="update_offer" class="dokan-btn dokan-btn-default dokan-btn-theme" value="update"><?php esc_attr_e( 'Update offer', 'dokan-lite' ); ?></button>
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
