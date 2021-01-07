<?php

global $myOffers, $rowPerPage, $currentPage, $allRow, $lastPage;
do_action('synerbay_init_global_my_offers_for_dashboard');

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
            do_action('dokan_offer_header');
            do_action('dokan_offer_content_inside_before');
            do_action('dokan_offer_filter');
            ?>

            <div class="product-listing-top dokan-clearfix">
                <?php if (isset($_GET['operation']) && $_GET['operation'] == 'success'): ?>
                    <div class="dokan-alert dokan-alert-success">
                        <a class="dokan-close" data-dismiss="alert">&times;</a>
                        <strong><?php esc_html_e('Success!', 'dokan-lite'); ?></strong>
                        <?php printf(__('Operation successful!', 'dokan-lite')); ?>
                    </div>
                <?php endif ?>
                <?php if (dokan_is_seller_enabled(get_current_user_id())): ?>
                    <span class="dokan-add-product-link">
                        <?php if (current_user_can('dokan_add_product')): ?>
                            <a href="<?php echo esc_url(dokan_get_navigation_url('new-offer')); ?>"
                               class="dokan-btn dokan-btn-theme <?php echo ('on' == dokan_get_option('disable_product_popup',
                                       'dokan_selling', 'off')) ? '' : 'dokan-add-new-product'; ?>">
                                <i class="fa fa-bullhorn">&nbsp;</i>
                                <?php esc_html_e('Add new Offer', 'dokan-lite'); ?>
                            </a>
                        <?php endif ?>
                        <?php
                        do_action('dokan_after_add_product_btn');
                        ?></span>
                <?php endif; ?>
            </div>
            </br>
            <table class="dokan-table dokan-table-striped product-listing-table dokan-inline-editable-table"
                   id="dokan-product-list-table">
                <thead>
                <tr>
                    <th><?php esc_html_e('ID', 'dokan-lite'); ?></th>
                    <th style="width: 130px;"><?php esc_html_e('Product Name', 'dokan-lite'); ?></th>
                    <th style="width: 100px;"><?php esc_html_e('Start Date', 'dokan-lite'); ?></th>
                    <th style="width: 100px;"><?php esc_html_e('End Date', 'dokan-lite'); ?></th>
                    <th style="width: 130px;"><?php esc_html_e('Delivery Date', 'dokan-lite'); ?></th>
                    <th><?php esc_html_e('Transport Parity'); ?></th>
                    <th><?php esc_html_e('Shipping To'); ?></th>
                    <th style="width: 100px;"><?php esc_html_e('Created At'); ?></th>
                    <th style="width: 175px;"><?php esc_html_e('Actions', 'dokan-lite'); ?></th>
                </tr>

                <?php
                $currentDate = strtotime(date('Y-m-d H:i:s'));

                if (count($myOffers)) {
                    foreach ($myOffers as $offer) {
                        $viewOfferButton = '<a class="dokan-btn dokan-btn-default dokan-btn-sm tips" target="_blank" href="' . $offer['url'] . '" data-toggle="tooltip" data-placement="top" title="" data-original-title="View offer"><i class="fa fa-eye">&nbsp;</i></a>';
                        $showButton = '<a class="dokan-btn dokan-btn-default dokan-btn-sm tips" target="_blank" href="/dashboard/show-offers/' . $offer['id'] . '" data-toggle="tooltip" data-placement="top" title="" data-original-title="Show"><i class="fa fa-users">&nbsp;</i></a>';
                        $updateButton = '';
                        $deleteButton = '';

                        if ($currentDate < strtotime($offer['offer_start_date'])) {
                            $updateButton = "<a class='dokan-btn dokan-btn-default dokan-btn-sm tips' href='/dashboard/edit-offer/" . $offer['id'] . "' data-toggle='tooltip' data-placement='top' title='' data-original-title='Edit offer'><i class='fa fa-pencil'>&nbsp;</i></a>";
                            $deleteButton = "<a class='dokan-btn dokan-btn-default dokan-btn-sm tips' onclick='window.synerbay.deleteOffer(" . $offer['id'] . ")' data-toggle='tooltip' data-placement='top' title='' data-original-title='Delete offer'><i class='fa fa-times'>&nbsp;</i></a>";
                        }

                        echo '<tr id="my_offer_row_' . $offer['id'] . '">'
                            . '<td>' . $offer['id'] . '</td>'
                            . '<td>' . $offer['product']['post_title'] . '</td>'
                            . '<td>' . date('Y-m-d', strtotime($offer['offer_start_date'])) . '</td>'
                            . '<td>' . date('Y-m-d', strtotime($offer['offer_end_date'])) . '</td>'
                            . '<td>' . date('Y-m-d', strtotime($offer['delivery_date'])) . '</td>'
                            . '<td>' . $offer['transport_parity'] . '</td>'
                            . '<td>' . $offer['shipping_to_labels'] . '</td>'
                            . '<td>' . date('Y-m-d', strtotime($offer['created_at'])) . '</td>'
                            . '<td class="dokan-order-action">' . $updateButton . $viewOfferButton . $showButton . $deleteButton . '</td>'
                            . '</tr>';
                    }
                } else {
                    echo '<td colspan="9">No offers found</td>';
                }

                ?>
                </thead>
            </table>

            <?php

            $base_url = dokan_get_navigation_url('my-offers');

            if ($lastPage > 1) {
                echo '<div class="pagination-wrap">';
                $page_links = paginate_links([
                    'current'  => $currentPage,
                    'total'    => $lastPage,
                    'base'     => $base_url . '%_%',
                    'format'   => '?pagenum=%#%',
                    'add_args' => false,
                    'type'     => 'array',
                ]);

                echo "<ul class='pagination'>\n\t<li>";
                echo join("</li>\n\t<li>", $page_links);
                echo "</li>\n</ul>\n";
                echo '</div>';
            }
            ?>


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