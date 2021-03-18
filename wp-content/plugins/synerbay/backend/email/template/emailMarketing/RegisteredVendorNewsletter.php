<?php require $headerPartialFile;?>

<p>
    A new vendor has registered on SynerBay from <?php echo $industryName; ?>.
</p>

<p>
    Click to see and get connected.<br>
    <?php

    $vendorSkeleton = '<li><a href="%s">%s</a></li>';

    echo '<ul>';
    foreach ($newVendors as $vendor) {
        $storeName = $vendor['data']['dokan_profile_settings']['store_name'];
        echo sprintf($vendorSkeleton, home_url('/store/' . $storeName), $storeName);
    }
    echo '</ul>';

    ?>
</p>

<?php require $footerPartialFile;?>