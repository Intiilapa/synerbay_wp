<?php require $headerPartialFile;?>

    <p>
        The seller rejected your request for the offer. Check the reason below.
    </p>
    <p>
        <strong>
            Reason:
        </strong>
        <br>
        <?php
            if (!empty($reason)) {
                echo $reason;
            } else {
                echo '-';
            }
        ?>
    </p>
    <p>
        View offer: <?php echo $url?>
    </p>

<?php require $footerPartialFile;?>