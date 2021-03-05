<?php require $headerPartialFile;?>

    <p>
        The offer is closed (#<?php echo $id?>)!
    </p>
    <p>
        <strong>Offer data:</strong><br>
        Offer ID: <?php echo $id?><br>
        Product price: <?php echo $summary['formatted_actual_product_price'];?><br>
        Applicants: <?php echo $summary['actual_applicant_number'];?><br>
        Successful: <strong><?php echo $summary['offer_qty_successful'] ? 'yes' : 'no';?></strong><br>
    </p>
    <p>
    <?php
    if (!$summary['offer_qty_successful']) {

        if ($summary['actual_applicant_number'] == 0) {
            echo 'You did not receive any order request. In further offers you can easily invite potential customers to buy.';
        } else {
            echo 'Congratulations for your sales. If you want to maximize your sales with further offers, you can easily invite potential customers to buy.';
        }

        if (count($applies) > $summary['actual_applicant_number']) {
            echo '<br>Important: Do not forget to accept your customers’ order requests!';
        }

    } else {
        echo 'Congratulations for successful offer fulfillment!';
    }
    ?>
    </p>
    <p>
        View offer: <?php echo $url?>
    </p>

<?php require $footerPartialFile;?>