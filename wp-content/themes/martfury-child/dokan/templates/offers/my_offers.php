<?php

global $myOffers;
do_action('synerbay_init_global_my_offers_for_dashboard');

/**
 *  dokan_new_product_wrap_before hook
 *
 *  @since 2.4
 */
do_action( 'dokan_new_product_wrap_before' );
?>

<?php do_action( 'dokan_dashboard_wrap_start' ); ?>

<div class="dokan-dashboard-wrap">
    <?php do_action( 'dokan_dashboard_content_before' ); ?>

    <div class="dokan-dashboard-content">

        <?php
            do_action('dokan_offer_header');
            do_action('dokan_offer_content_inside_before' );
            do_action('dokan_offer_filter');
        ?>

        <div class="product-listing-top dokan-clearfix">
            <?php if ( dokan_is_seller_enabled( get_current_user_id() ) ): ?>
                <span class="dokan-add-product-link">
                        <?php if ( current_user_can( 'dokan_add_product' ) ): ?>
                            <a href="<?php echo esc_url( dokan_get_navigation_url( 'new-offer' ) ); ?>" class="dokan-btn dokan-btn-theme <?php echo ( 'on' == dokan_get_option( 'disable_product_popup', 'dokan_selling', 'off' ) ) ? '' : 'dokan-add-new-product'; ?>">
                                <i class="fa fa-briefcase">&nbsp;</i>
                                <?php esc_html_e( 'Add new Offer', 'dokan-lite' ); ?>
                            </a>
                        <?php endif ?>
                    <?php
                        do_action( 'dokan_after_add_product_btn' );
                    ?></span>
            <?php endif; ?>
        </div>
        </br>
        <table class="dokan-table dokan-table-striped product-listing-table dokan-inline-editable-table" id="dokan-product-list-table">
            <thead>
            <tr>
                <th><?php esc_html_e( 'ID', 'dokan-lite' ); ?></th>
                <th><?php esc_html_e( 'Product Name', 'dokan-lite' ); ?></th>
                <th><?php esc_html_e( 'Delivery Date', 'dokan-lite' ); ?></th>
                <th><?php esc_html_e( 'Offer Start Date', 'dokan-lite' ); ?></th>
                <th><?php esc_html_e( 'Offer End Date', 'dokan-lite' ); ?></th>
                <th><?php esc_html_e( 'Min. Order Quantity' ); ?></th>
                <th><?php esc_html_e( 'Max Total Offer Quantity' ); ?></th>
                <th><?php esc_html_e( 'Transport Parity' ); ?></th>
                <th><?php esc_html_e( 'Shipping To' ); ?></th>
                <th><?php esc_html_e( 'Created At' ); ?></th>
                <th><?php esc_html_e( 'Details', 'dokan-lite' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'dokan-lite' ); ?></th>
            </tr>

        <?php
        $currentDate = strtotime(date('Y-m-d H:i:s'));

         foreach ($myOffers as $offer) {

             $updateButton = '-';
             $deleteButton = '-';

             if ($currentDate < strtotime($offer['offer_start_date'])) {
                 $updateButton = "<a href='/dashboard/edit-offer/".$offer['id']."' class='dokan-btn dokan-btn-theme'>Edit</a>";
                 $deleteButton = "<a onclick='window.synerbay.deleteOffer(".$offer['id'].")' class='dokan-btn dokan-btn-theme'>Delete</a>";
             }

             echo  '<tr id="my_offer_row_'.$offer['id'].'">'
                . '<td>'. $offer['id'] . '</td>'
                . '<td>'. $offer['product']['post_title'] . '</td>'
                . '<td>'. $offer['delivery_date'] . '</td>'
                . '<td>'. $offer['offer_start_date'] . '</td>'
                . '<td>'. $offer['offer_end_date'] . '</td>'
                . '<td>'. $offer['minimum_order_quantity'] . '</td>'
                . '<td>'. $offer['max_total_offer_qty'] . '</td>'
                . '<td>'. $offer['transport_parity'] . '</td>'
                . '<td>'. $offer['shipping_to_labels'] . '</td>'
                . '<td>'. $offer['created_at'] . '</td>'
                . '<td>'.'<a target="_blank" href="' . $offer['url'] . '">Details</a></td>'
                . '<td>'.$updateButton.$deleteButton.'</td>'
                . '</tr>';
        }

            if (!$myOffers){
                echo  '<td colspan="12">No offers found</td>';
            }
        ?>
            </thead>
        </table>
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