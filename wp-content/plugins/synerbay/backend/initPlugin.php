<?php
/**
 * init plugin actions
 */
namespace SynerBay;

use SynerBay\Element\OfferApplyElement;
use SynerBay\Element\SystemElement;
use SynerBay\Rest\OfferAppear;
use SynerBay\Rest\RFQ;

// HTML elements
new SystemElement();
new OfferApplyElement();

// REST
new OfferAppear();
new RFQ();
