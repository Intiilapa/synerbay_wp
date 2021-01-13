<?php require $headerPartialFile;?>

    <p>
        Jelentkezésed az eladó elutasította!>
    </p>
    <?php
        if (!empty($reason)) {
            echo '<p>'.$reason.'</p>';
        }
    ?>
    <p>
        View offer: <?php echo $url?>
    </p>

<?php require $footerPartialFile;?>