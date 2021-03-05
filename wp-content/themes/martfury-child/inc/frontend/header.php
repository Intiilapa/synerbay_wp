<?php


class Martfury_Child_Header{
    /**
     * Get header bar
     *
     * @since  1.0.0
     *
     *
     * @return string
     */
    function martfury_header_bar() {
        if ( ! intval( martfury_get_option( 'header_bar' ) ) ) {
            return;
        }

        ?>
        <div class="header-bar topbar">
            <?php
            $sidebar = 'header-bar';
            if ( is_active_sidebar( $sidebar ) ) {
                dynamic_sidebar( $sidebar );
            }
            do_action('synerbay_synerBayInviteButton');
            ?>
        </div>
        <?php
    }
}