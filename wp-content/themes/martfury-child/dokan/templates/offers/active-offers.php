<?php

use SynerBay\Helper\SynerBayDataHelper;

do_action('synerbay_init_global_my_offer_applies_for_dashboard');
global $myOfferApplies;

/**
*  dokan_new_product_wrap_before hook
*
* @since 2.4
*/
do_action('dokan_new_product_wrap_before');
?>

<?php do_action('dokan_dashboard_wrap_start'); ?>

<div class="dokan-dashboard-wrap">
    <?php do_action('dokan_dashboard_content_before'); ?>

    <div class="dokan-dashboard-content">

        <?php
        do_action('dokan_offer_content_inside_before');
        do_action('dokan_offer_filter');
        ?>

        <button class="dokan-btn dokan-btn-theme" onClick="window.location.reload();"><i
                    class="fa fa-refresh">&nbsp;</i> Refresh active offers
        </button>
        </br></br>
        <table class="dokan-table dokan-table-striped product-listing-table dokan-inline-editable-table"
               id="dokan-product-list-table">
            <thead>
            <tr>
                <th><?php esc_html_e('Offer ID', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Product name', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Quantity', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Current price', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Current quantity', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Offer end date', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Status', 'dokan-lite'); ?></th>
                <th><?php esc_html_e('Actions', 'dokan-lite'); ?></th>
            </tr>
            <?php
            $currentDate = strtotime(date('Y-m-d H:i:s'));
            foreach ($myOfferApplies as $offerApply) {

                $deleteButton = '';
                $showOfferButton = "<a href='" . $offerApply['offer']['url'] . "' class='dokan-btn dokan-btn-default dokan-btn-sm tips'data-toggle='tooltip' data-placement='top' title='' data-original-title='Details'><i class='fa fa-eye'>&nbsp;</i></a>";

                if ($currentDate <= strtotime($offerApply['offer']['offer_end_date'])) {
                    $deleteButton = "<a onclick='window.synerbay.disAppearOfferDashboard(" . $offerApply['offer_id'] . ")' class='dokan-btn dokan-btn-default dokan-btn-sm tips' data-toggle='tooltip' data-placement='top' title='' data-original-title='Delete'><i class='fa fa-times'>&nbsp;</i></a>";
                }

                echo '<tr id="my_active_offer_row_' . $offerApply['offer_id'] . '">'
                    . '<td>' . $offerApply['offer_id'] . '</td>'
                    . '<td>' . $offerApply['offer']['product']['post_title'] . '</td>'
                    . '<td>' . $offerApply['qty'] . '</td>'
                    . '<td><b>' . $offerApply['offer']['summary']['formatted_actual_product_price'] . '</b></td>'
                    . '<td><b>' . $offerApply['offer']['summary']['actual_applicant_product_number'] . '</b></td>'
                    . '<td><b>' . date('Y-m-d', strtotime($offerApply['offer']['offer_end_date'])) . '</b></td>'
                    . '<td><b>' . SynerBayDataHelper::offerAppearStatusLabel($offerApply['status']) . '</b></td>'
                    . '<td>' . $deleteButton . $showOfferButton . '</td>'
                    . '</tr>';
            }

            if (!$myOfferApplies) {
                echo '<td colspan="7">No active offers found</td>';
            } ?>
            </thead>
        </table>


        <?php

        /**
         *  dokan_after_new_product_inside_content_area hook
         *
         * @since 2.4
         */
        do_action('dokan_after_new_product_inside_content_area');
        ?>

    </div> <!-- #primary .content-area -->

    <?php

    /**
     *  dokan_dashboard_content_after hook
     *  dokan_after_new_product_content_area hook
     *
     * @since 2.4
     */
    do_action('dokan_dashboard_content_after');
    do_action('dokan_after_new_product_content_area');
    ?>

</div><!-- .dokan-dashboard-wrap -->

<?php do_action('dokan_dashboard_wrap_end'); ?>

<?php

/**
 *  dokan_new_product_wrap_after hook
 *
 * @since 2.4
 */
do_action('dokan_new_product_wrap_after');
?>
