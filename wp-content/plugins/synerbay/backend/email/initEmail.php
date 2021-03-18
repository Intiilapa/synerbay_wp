<?php

namespace SynerBay\Emails;

// default
include_once __DIR__ . '/service/AbstractEmail.php';
include_once __DIR__ . '/service/TestEmail.php';
include_once __DIR__ . '/service/InviteEmail.php';
include_once __DIR__ . '/service/InviteOfferEmail.php';
include_once __DIR__ . '/service/InviteProductEmail.php';

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

// email marketing
include_once __DIR__ . '/service/EmailMarketing/CompleteYourCatalogue.php';
include_once __DIR__ . '/service/EmailMarketing/HowToReachCustomers.php';
include_once __DIR__ . '/service/EmailMarketing/InviteYourPartners.php';
include_once __DIR__ . '/service/EmailMarketing/RecommendedOffers.php';
include_once __DIR__ . '/service/EmailMarketing/RecommendedProducts.php';
include_once __DIR__ . '/service/EmailMarketing/RegisteredVendorNewsletter.php';
include_once __DIR__ . '/service/EmailMarketing/RFQ.php';
include_once __DIR__ . '/service/EmailMarketing/SurplusStock.php';
include_once __DIR__ . '/service/EmailMarketing/SynerBaySocialIcon.php';
include_once __DIR__ . '/service/EmailMarketing/WeeklyAnalytics.php';
include_once __DIR__ . '/service/EmailMarketing/HintsAndTips.php';
include_once __DIR__ . '/service/EmailMarketing/B2BCrowdfunding.php';
