<?php require $headerPartialFile;?>

    <p>
        Here is the weekly collection of offers you may be interested in:<br>
        <?php

        $vendorSkeleton = '<li><a href="%s">%s</a> (Start date: %s)</li>';

        echo '<ul>';
        foreach ($newOffers as $offer) {
            echo sprintf($vendorSkeleton, $offer['url'], $offer['product']['name'], date('Y-m-d', strtotime($offer['offer_start_date'])));
        }
        echo '</ul>';

        ?>
    </p>

<?php require $footerPartialFile;?>