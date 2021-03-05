<?php require $headerPartialFile;?>

    <p>
        The offer is ended. Check the details below. (#<?php echo $id?>)!
    </p>
    <p>
        <strong>Offer data:</strong><br>
        Offer ID: <?php echo $id?><br>
        Product price: <?php echo $summary['formatted_actual_product_price'];?><br>
        Your QTY: [qty]<br>
        Grand total: [grand_total]<br>
        Applicants: <?php echo $summary['actual_applicant_number'];?><br>
    </p>
    <p>
        View offer: <?php echo $url?>
    </p>

<?php require $footerPartialFile;?>