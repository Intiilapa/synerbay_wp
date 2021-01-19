<?php
// akkor megy ki, ha elindul az offer (rfq és follower usereknek)
require $headerPartialFile;
?>
    <p>
        Az offer elindult. <br>
        Azért kaptad ezt az e-mail-t mert vagy követed az eladót, vagy igény adtál be a termékre (RFQ).
    </p>
    <p>
        View offer: <?php echo $url?>
    </p>
<?php require $footerPartialFile;?>