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
        Commission price: <strong><?php echo $summary['formatted_actual_commission_price'];?></strong><br>
        Fictitious commission price: <strong><?php echo $summary['formatted_fictitious_commission_price'];?></strong><br>
    </p>
    <p>
        <?php
        if (!$summary['offer_qty_successful']) {

            if ($summary['actual_applicant_number'] == 0) {
                echo 'Nem volt jelentkező, vagy nem hagyta jóvá!';
            } else {
                echo 'Volt jelentkező, de nem érte el a max price step qty-t!';
            }

        } else {
            echo 'Sikeresen zárult!';
        }
        ?>
    </p>
    <p>
        View offer: <?php echo $url?>
    </p>

<?php require $footerPartialFile;?>