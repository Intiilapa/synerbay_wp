<?php
namespace SynerBay\HTMLElement;

include_once 'AbstractElement.php';
include_once 'SystemElement.php';
include_once 'TextElement.php';
include_once 'SelectElement.php';
include_once 'OfferApplyElement.php';
include_once 'RFQElement.php';
include_once 'JavascriptElements.php';

new SystemElement();
new SelectElement();
new TextElement();
new OfferApplyElement();
new RFQElement();
new JavascriptElements();