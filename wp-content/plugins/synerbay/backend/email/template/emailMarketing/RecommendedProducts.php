<?php require $headerPartialFile;?>

    <p>
        Here is the weekly collection of products you may be interested in:<br>
        <?php

        $vendorSkeleton = '<li><a href="%s">%s</a></li>';

        echo '<ul>';
        foreach ($newProducts as $product) {
            echo sprintf($vendorSkeleton, $product['url'], $product['name']);
        }
        echo '</ul>';

        ?>
    </p>

<?php require $footerPartialFile;?>