<?php
/**
 * Dokan Review Status Filter Template
 *
 * @since 2.4
 *
 * @package dokan
 */
?>
<div id="dokan-offers_menu">
    <ul class="dokan_tabs">
        <li class="<?php if (strpos($_SERVER['REQUEST_URI'], '/orders/') !== false){ echo 'active'; }?>">
            <a href="/dashboard/orders">Orders</a>
        </li>
        <li class="<?php if (strpos($_SERVER['REQUEST_URI'], '/active-offers/') !== false){ echo 'active'; }?>">
            <a href="/dashboard/active-offers">Active Offers</a>
        </li>
    </ul>
</div>
