<?php

do_action('synerbay_init_global_my_offer_applies_for_dashboard');
global $myOfferApplies;
?>
<h1>Active offers</h1>
<hr>
<table class="dokan-table dokan-table-striped product-listing-table dokan-inline-editable-table" id="dokan-product-list-table">
    <thead>
    <tr>
        <th><?php esc_html_e( 'Offer ID', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Offer Name', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Quantity', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Current Price', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Applicant Product Number', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( '', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Actions', 'dokan-lite' ); ?></th>
    </tr>

<?php
foreach ($myOfferApplies as $offerApply) {
    echo  '<tr>'
        . '<td>'. $offerApply['offer_id'] . '</td>'
        . '<td>'. $offerApply['offer']['product']['post_title'] . '</td>'
        . '<td>'. $offerApply['qty'] . '</td>'
        . '<td>'. $offerApply['offer']['summary']['formatted_actual_product_price'] . '</td>'
        . '<td>'. $offerApply['offer']['summary']['actual_applicant_product_number'] . '</td>'
        . '<td>'.'<a href="stage.synerbay.com/offer/' . $offerApply['offer_id'] . '">Details</a></td>'
        . '<td>'."<a onclick='window.synerbay.disAppearOffer(".$offerApply['offer_id'].")' class='dokan-btn dokan-btn-default dokan-btn-sm tips'>x</a>".'</td>'
        . '</tr>';
}
//do_action('synerbay_disAppearOfferDashBoardButton', $offerApply['offer_id'])
?>
</thead>
</table>
