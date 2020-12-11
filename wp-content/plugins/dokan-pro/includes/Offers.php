<?php


namespace WeDevs\DokanPro;


class Offers{

    /**
     * Load automatically when class inistantiate
     *
     * @since 2.4
     *
     * @uses actions|filter hooks
     */
    public function __construct() {
        add_action( 'dokan_offer_content_inside_before', array( $this, 'show_seller_enable_message' ) );
        add_filter( 'dokan_get_dashboard_nav', array( $this, 'add_offers_menu' ) );
        add_action( 'dokan_load_custom_template', array( $this, 'load_offers_template' ) );
        add_action( 'dokan_offer_content_area_header', array( $this, 'offer_header_render' ) );
        add_action( 'dokan_offer_content', array( $this, 'render_review_content' ) );
        add_action( 'template_redirect', array( $this, 'handle_statement' ) );
    }

    /**
     * Export statement
     *
     * @return vois
     */
    function handle_statement() {
        if ( ! is_user_logged_in() ) {
            return;
        }

        if ( ! dokan_is_user_seller( get_current_user_id() ) ) {
            return;
        }

        if ( isset( $_GET['dokan_statement_export_all'] ) ) {
            $start_date = date( 'Y-m-01', current_time('timestamp') );
            $end_date = date( 'Y-m-d', strtotime( 'midnight', current_time( 'timestamp' ) ) );

            if ( isset( $_GET['start_date'] ) ) {
                $start_date = date( 'Y-m-d', strtotime( $_GET['start_date'] ) );
            }

            if ( isset( $_GET['end_date'] ) ) {
                $end_date = date( 'Y-m-d', strtotime( $_GET['end_date'] ) );
            }

            $filename = "Statement-".date( 'Y-m-d',time() );
            header( "Content-Type: application/csv; charset=" . get_option( 'blog_charset' ) );
            header( "Content-Disposition: attachment; filename=$filename.csv" );
            $currency = get_woocommerce_currency_symbol();
            $headers  = array(
                'date'         => __( 'Trn Date', 'dokan' ),
                'balance_date' => __( 'Balance Date', 'dokan' ),
                'id'           => __( 'ID', 'dokan' ),
                'type'         => __( 'Type', 'dokan' ),
                'debit'        => __( 'Debit', 'dokan' ),
                'credit'       => __( 'Credit', 'dokan' ),
                'balance'      => __( 'Balance', 'dokan' ),
            );

            foreach ( (array)$headers as $label ) {
                echo $label .', ';
            }

            echo "\r\n";

            //calculate opening balance
            global $wpdb;
            $vendor = dokan()->vendor->get( dokan_get_current_user_id() );
            $opening_balance = $vendor->get_balance( false, date( 'Y-m-d', strtotime( $start_date . ' -1 days' ) ) );
            $status = implode( "', '", dokan_withdraw_get_active_order_status() );

            $sql = "SELECT * from {$wpdb->prefix}dokan_vendor_balance WHERE vendor_id = %d AND DATE(balance_date) >= %s AND DATE(balance_date) <= %s AND ( ( trn_type = 'dokan_orders' AND status IN ('{$status}') ) OR trn_type IN ( 'dokan_withdraw', 'dokan_refund' ) ) ORDER BY balance_date";

            $statements = $wpdb->get_results( $wpdb->prepare( $sql, $vendor->id, $start_date, $end_date ) );

            echo $start_date . ', ';
            echo '--' . ', ';
            echo '#' .'--' . ', ';
            echo 'Opening Balance' . ', ';
            echo '--' . ', ';
            echo '--' . ', ';
            echo $opening_balance . ', ';
            echo "\r\n";

            $balance = $opening_balance;
            foreach ( $statements as $key => $statement ) {

                $balance += $statement->debit - $statement->credit;

                switch ( $statement->trn_type ) {
                    case 'dokan_orders':
                        $type = __( 'Order', 'dokan' );
                        break;

                    case 'dokan_withdraw':
                        $type = __( 'Withdraw', 'dokan' );
                        break;

                    case 'dokan_refund':
                        $type = __( 'Refund', 'dokan' );
                        break;
                }

                echo date( 'Y-m-d', strtotime( $statement->trn_date ) ) . ', ';
                echo date( 'Y-m-d', strtotime( $statement->balance_date ) ) . ', ';
                echo '#' . $statement->trn_id . ', ';
                echo $type . ', ';
                echo $statement->debit . ', ';
                echo $statement->credit . ', ';
                echo $balance . ', ';

                echo "\r\n";
            }

            exit();
        }
    }

    /**
     * Show Seller Enable Error Message
     *
     * @since 2.4
     *
     * @return void
     */
    public function show_seller_enable_message() {
        $user_id = get_current_user_id();

        if ( ! dokan_is_seller_enabled( $user_id ) ) {
            echo dokan_seller_not_enabled_notice();
        }
    }

    /**
     * Add offer Menu
     *
     * @since 2.4
     *
     * @param array $urls
     *
     * @return array
     */
    public function add_offers_menu( $urls ) {
        $urls['offers'] = array(
            'title' => __( 'offers', 'dokan' ),
            'icon'  => '<i class="fa fa-line-chart"></i>',
            'url'   => dokan_get_navigation_url( 'offers' ),
            'pos'   => 60,
            'permission' => 'dokan_view_offer_menu'
        );

        return $urls;
    }

    /**
     * Load offer Main Template
     *
     * @since 2.4
     *
     * @param  array $query_vars
     *
     * @return void
     */
    public function load_offers_template( $query_vars ) {
        if ( isset( $query_vars['offers'] ) ) {
            if ( ! current_user_can( 'dokan_view_review_menu' ) ) {
                dokan_get_template_part('global/dokan-error', '', array( 'deleted' => false, 'message' => __( 'You have no permission to view review page', 'dokan' ) ) );
                return;
            } else {
                dokan_get_template_part( 'offer/offer', '', array( 'pro' => true ) );
                return;
            }
        }
    }

    /**
     * Render Report Header Template
     *
     * @since 2.4
     *
     * @return void
     */
    public function offer_header_render() {
        dokan_get_template_part( 'offer/header', '', array( 'pro' => true ) );
    }

    /**
     * Render Review Content
     *
     * @return void
     */
    public function render_review_content() {
        global $woocommerce;

        $charts  = dokan_get_reports_charts();
        $link    = dokan_get_navigation_url( 'reports' );
        $current = isset( $_GET['chart'] ) ? $_GET['chart'] : 'overview';

        dokan_get_template_part( 'report/content', '', array(
            'pro' => true,
            'charts' => $charts,
            'link' => $link,
            'current' => $current,
        ) );
    }
}