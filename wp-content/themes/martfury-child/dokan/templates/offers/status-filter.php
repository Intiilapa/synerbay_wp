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
        <li class="<?php if (strpos($_SERVER['REQUEST_URI'], '/offer/') !== false){ echo 'active'; }?>">
            <a href="/dashboard/offer">Active Offers</a>
        </li>
        <li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'my-offers/') !== false){ echo 'active'; }?>">
            <a href="/dashboard/my-offers">My Offers</a>
        </li>
        <li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'show-offers/') !== false){ echo 'active'; }?>">
            <a href="/dashboard/show-offers">Show Offers</a>
        </li>
    </ul>
</div>
