<?php

global $myOffers;
do_action('synerbay_init_global_my_offers_for_dashboard');
?>
<div class="product-listing-top dokan-clearfix">
    <h1>My Offers</h1>
    <?php if ( dokan_is_seller_enabled( get_current_user_id() ) ): ?>
        <span class="dokan-add-product-link">
                            <?php if ( current_user_can( 'dokan_add_product' ) ): ?>
                                <a href="<?php echo esc_url( dokan_get_navigation_url( 'new-product' ) ); ?>" class="dokan-btn dokan-btn-theme <?php echo ( 'on' == dokan_get_option( 'disable_product_popup', 'dokan_selling', 'on' ) ) ? '' : 'dokan-add-new-product'; ?>">
                                    <i class="fa fa-briefcase">&nbsp;</i>
                                    <?php esc_html_e( 'Add new Offer', 'dokan-lite' ); ?>
                                </a>
                            <?php endif ?>

            <?php
            do_action( 'dokan_after_add_product_btn' );
            ?>
                        </span>
    <?php endif; ?>
</div>

<table class="dokan-table dokan-table-striped product-listing-table dokan-inline-editable-table" id="dokan-product-list-table">
    <thead>
    <tr>
        <th><?php esc_html_e( 'Offer Name', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Offer ID', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Delivery Date', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Offer Start Date', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Offer End Date', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Min. Order Quantity' ); ?></th>
        <th><?php esc_html_e( 'Order Quantity Steps' ); ?></th>
        <th><?php esc_html_e( 'Max Total Offer Quantity' ); ?></th>
        <th><?php esc_html_e( 'Transport Parity' ); ?></th>
        <th><?php esc_html_e( 'Shipping To' ); ?></th>
        <th><?php esc_html_e( 'Created At' ); ?></th>
    </tr>

<?php
 foreach ($myOffers as $offer) {
    echo  '<tr>'
        . '<td>'. $offer['product']['post_title'] . '</td>'
        . '<td>'. $offer['product_id'] . '</td>'
        . '<td>'. $offer['delivery_date'] . '</td>'
        . '<td>'. $offer['offer_start_date'] . '</td>'
        . '<td>'. $offer['offer_end_date'] . '</td>'
        . '<td>'. $offer['minimum_order_quantity'] . '</td>'
        . '<td>'. $offer['order_quantity_step'] . '</td>'
        . '<td>'. $offer['max_total_offer_qty'] . '</td>'
        . '<td>'. $offer['transport_parity'] . '</td>'
        . '<td>'. $offer['shipping_to'] . '</td>'
        . '<td>'. $offer['created_at'] . '</td>'
        . '</tr>';
}
?>

</thead>
</table>
