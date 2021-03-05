<?php
global $offer;

/**
 *  dokan_new_product_wrap_before hook
 *
 *  @since 2.4
 */

use SynerBay\Model\OfferApply;

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

        <header class="dokan-dashboard-header dokan-clearfix">
            <h1 class="entry-title">
                <?php esc_html_e( 'Show Offer', 'dokan-lite' ); ?> (<?php echo $offer['id'];?>)
            </h1>
        </header>

        <div class="notice-box">
            <span class="notice-title">Offer data:</span>
        </div>

        <table class="dokan-table dokan-table-striped product-listing-table dokan-inline-editable-table"
               id="dokan-product-list-table">
            <thead>
            <tr>
                <th><?php esc_html_e('Product Name', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Current price', 'dokan-lite'); ?></th>
                <th style="width: 100px;"><?php esc_html_e('Start Date', 'dokan-lite'); ?></th>
                <th style="width: 100px;"><?php esc_html_e('End Date', 'dokan-lite'); ?></th>
                <th style="width: 130px;"><?php esc_html_e('Delivery Date', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Transport Parity'); ?></th>
                <th><?php esc_html_e('Shipping To'); ?></th>
                <th style="width: 100px;"><?php esc_html_e('Created At'); ?></th>
            </tr>

            <?php

                echo '<tr id="my_offer_row_' . $offer['id'] . '">'
                    . '<td>' . $offer['product']['post_title'] . '</td>'
                    . '<td>' . $offer['summary']['formatted_actual_product_price'] . '</td>'
                    . '<td>' . date('Y-m-d', strtotime($offer['offer_start_date'])) . '</td>'
                    . '<td>' . date('Y-m-d', strtotime($offer['offer_end_date'])) . '</td>'
                    . '<td>' . date('Y-m-d', strtotime($offer['delivery_date'])) . '</td>'
                    . '<td>' . $offer['transport_parity'] . '</td>'
                    . '<td>' . $offer['shipping_to_labels'] . '</td>'
                    . '<td>' . date('Y-m-d', strtotime($offer['created_at'])) . '</td>'
                    . '</tr>';

            ?>
            </thead>
        </table>

        <div class="notice-box">
        <span class="notice-title">Applicant data:</span></br></br>
        <span>
            <b>IMPORTANT!</b></br>
            You have to accept customer requests to change pending status to accepted order.</br>
            You can easily contact potential customers before being sure of trustworthiness.</br>
            Once you have accepted the order request, you can not change later!</br>
            Until a customer is in pending status, its order need is not added to the offer az a placed order.</br>
        </span>
        </div>

        <table class="dokan-table dokan-table-striped product-listing-table dokan-inline-editable-table"
               id="dokan-product-list-table">
            <thead>
            <tr>
                <th><?php esc_html_e('Vendor name', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Contact name', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Contact e-mail', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Contact phone', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('QTY'); ?></th>
                <th><?php esc_html_e('Status'); ?></th>
                <th style="width: 160px;"><?php esc_html_e('Apply date'); ?></th>
                <th><?php esc_html_e('Actions', 'dokan-lite'); ?></th>
            </tr>

            <?php
            if (count($offer['applies'])) {
                foreach ($offer['applies'] as $apply) {
                    /** @var Dokan_Vendor $dokanCustomer */
                    $dokanCustomer = $apply['customer'];

                    $viewButton = '<a class="dokan-btn dokan-btn-default dokan-btn-sm tips" target="_blank" href="' . $dokanCustomer->get_shop_url() . '" data-toggle="tooltip" data-placement="top" title="" data-original-title="View vendor"><i class="fa fa-eye">&nbsp;</i></a>';

                    if ($apply['status'] == OfferApply::STATUS_PENDING) {
                        $viewButton .= '<a class="dokan-btn dokan-btn-default dokan-btn-sm tips" onclick="synerbay.acceptApply(' . $apply['id'] . ')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Accept"><i class="fa fa-check">&nbsp;</i></a>';
                        $viewButton .= '<a class="dokan-btn dokan-btn-default dokan-btn-sm tips" onclick="synerbay.rejectApply(' . $apply['id'] . ')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reject"><i class="fa fa-trash">&nbsp;</i></a>';
                    }

                    echo '<tr id="my_offer_apply_row_' . $apply['id'] . '">'
                        . '<td>' . $dokanCustomer->get_shop_name() . '</td>'
                        . '<td>' . $dokanCustomer->get_name() . '</td>'
                        . '<td>' . $dokanCustomer->get_email() . '</td>'
                        . '<td>' . $dokanCustomer->get_phone() . '</td>'
                        . '<td>' . $apply['qty'] . '</td>'
                        . '<td>' . $apply['status'] . '</td>'
                        . '<td>' . $apply['created_at'] . '</td>'
                        . '<td class="dokan-order-action">' . $viewButton . '</td>'
                        . '</tr>';
                }
            } else {
                echo '<td colspan="9">No applicant found</td>';
            }

            ?>
            </thead>
        </table>

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
