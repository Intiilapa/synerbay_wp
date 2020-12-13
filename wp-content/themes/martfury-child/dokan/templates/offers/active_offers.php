<?php
echo 'Active Table.php';

global $post;

$user_id   = get_current_user_id();
$offer_id  = get_post_meta($user_id, 'offer_id', true);



echo $offer_id;





