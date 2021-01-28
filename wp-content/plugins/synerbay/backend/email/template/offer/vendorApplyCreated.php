<?php require $headerPartialFile;?>

    <p>
        You have received an order request from /customer name/. You can accept or reject the order. As soon as you accept, we will add the ordered quantity to your offer.
        In case if you do not accept the order request, we delete that when the offer ends.<br>
        <strong>Important:</strong> If you do not know the customer, we propose you to contact them before you accept its request.
    </p>
    <p>
        View offer: <?php echo $url?>
    </p>
    <p>
        Go to dashboard: <?php echo $dashboardUrl?>
    </p>

<?php require $footerPartialFile;?>