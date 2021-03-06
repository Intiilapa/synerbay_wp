<?php


namespace WeDevs\Dokan\Dashboard\Templates;


class Offers{


    /**
     * Load autometically when class inistantiate
     * hooked up all actions and filters
     *
     * @since 2.4
     */
    function __construct() {
        //add_action( 'template_redirect', array( $this, 'handle_offer_export' ) );
        add_action( 'dokan_offer_content_inside_before', array( $this, 'show_seller_enable_message' ) );
        add_action( 'dokan_offer_inside_content', array( $this, 'offer_listing_status_filter' ), 10 );
        add_action( 'dokan_offer_inside_content', array( $this, 'offer_main_content' ), 15 );
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
            echo esc_html( dokan_seller_not_enabled_notice() );
        }
    }

    /**
     * Render offer listing status filter template
     *
     * @since 2.4
     *
     * @return void
     */
    public function offer_listing_status_filter() {
        dokan_get_template_part( 'orders/orders-status-filter' );
    }

    /**
     * Get Order Main Content
     *
     * @since 2.4
     *
     * @return void
     */
    public function offer_main_content() {
        $order_id = isset( $_GET['order_id'] ) ? intval( $_GET['order_id'] ) : 0;

        if ( $order_id ) {
            $_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';

            if ( wp_verify_nonce( $_nonce, 'dokan_view_offer' ) && current_user_can( 'dokan_view_offer' ) ) {
                dokan_get_template_part( 'offers/details' );
            } else if ( isset ( $_REQUEST['_view_mode'] ) && 'email' == $_REQUEST['_view_mode'] && current_user_can( 'dokan_view_offer' ) ) {
                dokan_get_template_part( 'offers/details' );
            } else {
                dokan_get_template_part( 'global/dokan-error', '', array( 'deleted' => false, 'message' => __( 'You have no permission to view this offer', 'dokan-lite' ) ) );
            }
        } else {
            //dokan_get_template_part( 'offers/date-export' );
            dokan_get_template_part( 'offers/listing' );
        }
    }
}