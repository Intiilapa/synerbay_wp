<?php

do_action('synerbay_init_global_my_offer_applies_for_dashboard');
global $myOfferApplies;

?>

<button class="dokan-btn dokan-btn-theme" onClick="window.location.reload();"><i class="fa fa-refresh">&nbsp;</i> Refresh active offers</button></br></br>
<table class="dokan-table dokan-table-striped product-listing-table dokan-inline-editable-table" id="dokan-product-list-table">
    <thead>
    <tr>
        <th><?php esc_html_e( 'Offer ID', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Product name', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Quantity', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Current Price', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Current quantity', 'dokan-lite' ); ?></th>
        <th><?php esc_html_e( 'Actions', 'dokan-lite' ); ?></th>
    </tr>

<?php
$currentDate = strtotime(date('Y-m-d H:i:s'));
foreach ($myOfferApplies as $offerApply) {

    $deleteButton = '';
    $showOfferButton = "<a href='". $offerApply['offer']['url'] ."' class='dokan-btn dokan-btn-default dokan-btn-sm tips'data-toggle='tooltip' data-placement='top' title='' data-original-title='Details'><i class='fa fa-eye'>&nbsp;</i></a>";

    if ($currentDate <= strtotime($offerApply['offer']['offer_end_date'])) {
        $deleteButton = "<a onclick='window.synerbay.disAppearOfferDashboard(".$offerApply['offer_id'].")' class='dokan-btn dokan-btn-default dokan-btn-sm tips'data-toggle='tooltip' data-placement='top' title='' data-original-title='Delete'><i class='fa fa-times'>&nbsp;</i></a>";
    }

    echo  '<tr id="my_active_offer_row_'.$offerApply['offer_id'].'">'
        . '<td>'. $offerApply['offer_id'] . '</td>'
        . '<td>'. $offerApply['offer']['product']['post_title'] . '</td>'
        . '<td>'. $offerApply['qty'] . '</td>'
        . '<td><b>'. $offerApply['offer']['summary']['formatted_actual_product_price'] . '</b></td>'
        . '<td><b>'. $offerApply['offer']['summary']['actual_applicant_product_number'] . '</b></td>'
        . '<td>'. $deleteButton. $showOfferButton.'</td>'
        . '</tr>';
}

if (!$myOfferApplies){
    echo  '<td colspan="7">No active offers found</td>';
}
//do_action("synerbay_disAppearOfferDashBoardButton', $offerApply['offer_id'])
?>
</thead>
</table>
