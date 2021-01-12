<?php

namespace SynerBay\Emails;

// default
include_once __DIR__ . '/service/AbstractEmail.php';
include_once __DIR__ . '/service/TestEmail.php';
include_once __DIR__ . '/service/InviteEmail.php';

// vendor
include_once __DIR__ . '/service/Offer/Vendor/ApplyModified.php';
include_once __DIR__ . '/service/Product/CreateRFQVendor.php';

//customer
include_once __DIR__ . '/service/Offer/Customer/ApplyCreated.php';
include_once __DIR__ . '/service/Offer/Customer/ApplyModified.php';