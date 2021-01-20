<?php

namespace SynerBay\Emails;

// default
include_once __DIR__ . '/service/AbstractEmail.php';
include_once __DIR__ . '/service/TestEmail.php';
include_once __DIR__ . '/service/InviteEmail.php';

// admin
include_once __DIR__ . '/service/Offer/Admin/OfferEnded.php';
include_once __DIR__ . '/service/Offer/Admin/OfferStarted.php';

// vendor
include_once __DIR__ . '/service/Offer/Vendor/ApplyCreated.php';
include_once __DIR__ . '/service/Offer/Vendor/ApplyModified.php';
include_once __DIR__ . '/service/Offer/Vendor/OfferEnded.php';
include_once __DIR__ . '/service/Product/CreateRFQVendor.php';

//customer
include_once __DIR__ . '/service/Offer/Customer/ApplyCreated.php';
include_once __DIR__ . '/service/Offer/Customer/ApplyModified.php';
include_once __DIR__ . '/service/Offer/Customer/ApplyAccepted.php';
include_once __DIR__ . '/service/Offer/Customer/ApplyDenied.php';
include_once __DIR__ . '/service/Offer/Customer/FollowerOfferStarted.php';
include_once __DIR__ . '/service/Offer/Customer/RFQUserOfferStarted.php';
include_once __DIR__ . '/service/Offer/Customer/OfferEnded.php';