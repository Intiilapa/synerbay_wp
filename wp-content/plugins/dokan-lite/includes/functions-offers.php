<?php


function say_something(){
    echo 'y';
}

function dokan_offer_listing_status_filter() {
    $_get_data = wp_unslash( $_GET );

    $orders_url = dokan_get_navigation_url( 'orders' );

    $status_class         = isset( $_get_data['order_status'] ) ? $_get_data['order_status'] : 'all';
    $orders_counts        = dokan_count_orders( dokan_get_current_user_id() );
    $order_date           = ( isset( $_get_data['order_date'] ) ) ? $_get_data['order_date'] : '';
    $date_filter          = array();
    $all_order_url        = array();
    $complete_order_url   = array();
    $processing_order_url = array();
    $pending_order_url    = array();
    $on_hold_order_url    = array();
    $canceled_order_url   = array();
    $refund_order_url     = array();
    $failed_order_url     = array();
    ?>

    <ul class="list-inline order-statuses-filter">
        <li<?php echo $status_class == 'all' ? ' class="active"' : ''; ?>>
            <?php
            if ( $order_date ) {
                $date_filter = array(
                    'order_date' => $order_date,
                    'dokan_order_filter' => 'Filter',
                );
            }
            $all_order_url = array_merge( $date_filter, array( 'order_status' => 'all' ) );
            $all_order_url = ( empty( $all_order_url ) ) ? $orders_url : add_query_arg( $complete_order_url, $orders_url );
            ?>
            <a href="<?php echo esc_url( $all_order_url ); ?>">
                <?php printf( esc_html__( 'All (%d)', 'dokan-lite' ), esc_attr( $orders_counts->total ) ); ?></span>
            </a>
        </li>
        <li<?php echo $status_class == 'wc-completed' ? ' class="active"' : ''; ?>>
            <?php
            if ( $order_date ) {
                $date_filter = array(
                    'order_date' => $order_date,
                    'dokan_order_filter' => 'Filter',
                );
            }
            $complete_order_url = array_merge( array( 'order_status' => 'wc-completed' ), $date_filter );
            ?>
            <a href="<?php echo esc_url( add_query_arg( $complete_order_url, $orders_url ) ); ?>">
                <?php printf( esc_html__( 'Completed (%d)', 'dokan-lite' ), esc_attr( $orders_counts->{'wc-completed'} ) ); ?></span>
            </a>
        </li>
        <li<?php echo $status_class == 'wc-processing' ? ' class="active"' : ''; ?>>
            <?php
            if ( $order_date ) {
                $date_filter = array(
                    'order_date' => $order_date,
                    'dokan_order_filter' => 'Filter',
                );
            }
            $processing_order_url = array_merge( $date_filter, array( 'order_status' => 'wc-processing' ) );
            ?>
            <a href="<?php echo esc_url( add_query_arg( $processing_order_url, $orders_url ) ); ?>">
                <?php printf( esc_html__( 'Processing (%d)', 'dokan-lite' ), esc_attr( $orders_counts->{'wc-processing'} ) ); ?></span>
            </a>
        </li>
        <li<?php echo $status_class == 'wc-on-hold' ? ' class="active"' : ''; ?>>
            <?php
            if ( $order_date ) {
                $date_filter = array(
                    'order_date' => $order_date,
                    'dokan_order_filter' => 'Filter',
                );
            }
            $on_hold_order_url = array_merge( $date_filter, array( 'order_status' => 'wc-on-hold' ) );
            ?>
            <a href="<?php echo esc_url( add_query_arg( $on_hold_order_url, $orders_url ) ); ?>">
                <?php printf( esc_html__( 'On-hold (%d)', 'dokan-lite' ), esc_attr( $orders_counts->{'wc-on-hold'} ) ); ?></span>
            </a>
        </li>
        <li<?php echo $status_class == 'wc-pending' ? ' class="active"' : ''; ?>>
            <?php
            if ( $order_date ) {
                $date_filter = array(
                    'order_date' => $order_date,
                    'dokan_order_filter' => 'Filter',
                );
            }
            $pending_order_url = array_merge( $date_filter, array( 'order_status' => 'wc-pending' ) );
            ?>
            <a href="<?php echo esc_url( add_query_arg( $pending_order_url, $orders_url ) ); ?>">
                <?php printf( esc_html__( 'Pending (%d)', 'dokan-lite' ), esc_attr( $orders_counts->{'wc-pending'} ) ); ?></span>
            </a>
        </li>
        <li<?php echo $status_class == 'wc-canceled' ? ' class="active"' : ''; ?>>
            <?php
            if ( $order_date ) {
                $date_filter = array(
                    'order_date' => $order_date,
                    'dokan_order_filter' => 'Filter',
                );
            }
            $canceled_order_url = array_merge( $date_filter, array( 'order_status' => 'wc-cancelled' ) );
            ?>
            <a href="<?php echo esc_url( add_query_arg( $canceled_order_url, $orders_url ) ); ?>">
                <?php printf( esc_html__( 'Cancelled (%d)', 'dokan-lite' ), esc_attr( $orders_counts->{'wc-cancelled'} ) ); ?></span>
            </a>
        </li>
        <li<?php echo $status_class == 'wc-refunded' ? ' class="active"' : ''; ?>>
            <?php
            if ( $order_date ) {
                $date_filter = array(
                    'order_date' => $order_date,
                    'dokan_order_filter' => 'Filter',
                );
            }
            $refund_order_url = array_merge( $date_filter, array( 'order_status' => 'wc-refunded' ) );
            ?>
            <a href="<?php echo esc_url( add_query_arg( $refund_order_url, $orders_url ) ); ?>">
                <?php printf( esc_html__( 'Refunded (%d)', 'dokan-lite' ), esc_attr( $orders_counts->{'wc-refunded'} ) ); ?></span>
            </a>
        </li>
        <li<?php echo $status_class == 'wc-failed' ? ' class="active"' : ''; ?>>
            <?php
            if ( $order_date ) {
                $date_filter = array(
                    'order_date'         => $order_date,
                    'dokan_order_filter' => 'Filter',
                );
            }

            $failed_order_url = array_merge( $date_filter, array( 'order_status' => 'wc-failed' ) );
            ?>
            <a href="<?php echo esc_url( add_query_arg( $failed_order_url, $orders_url ) ); ?>">
                <?php printf( esc_html__( 'Failed (%d)', 'dokan-lite' ), esc_attr( $orders_counts->{'wc-failed'} ) ); ?></span>
            </a>
        </li>

        <?php do_action( 'dokan_status_listing_item', $orders_counts ); ?>
    </ul>
    <?php
}