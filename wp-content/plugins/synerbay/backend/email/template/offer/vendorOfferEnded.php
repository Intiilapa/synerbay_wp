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
            echo 'Nem volt jelentkező, bíztatni kell, hogy hívja be a partnereit!';
        } else {
            echo 'Volt jelentkező, de nem érte el a max price step qty-t! Itt is bíztatni kell!';
        }

        if (count($applies) > $summary['actual_applicant_number']) {
            echo '<br>Információ: valami olyan szöveg kell, hogy ne felejtsd el minden esetben a beérkező igényeket jóváhagyni!';
        }

    } else {
        echo 'Minden siker, gratulálunk!';
    }
    ?>
    </p>
    <p>
        View offer: <?php echo $url?>
    </p>

<?php require $footerPartialFile;?>