<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_email_header', $emailHeading, $email ); ?>

<p>
    Dear <?php echo $consigneeName;?>!
</p>
