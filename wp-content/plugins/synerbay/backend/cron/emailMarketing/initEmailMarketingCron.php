<?php
namespace SynerBay\Cron\EmailMarketing;

require_once __DIR__ . '/CompleteYourCatalogue.php';
require_once __DIR__ . '/HowToReachCustomers.php';
require_once __DIR__ . '/InviteYourPartners.php';
require_once __DIR__ . '/RecommendedOffers.php';
require_once __DIR__ . '/RecommendedProducts.php';
require_once __DIR__ . '/RegisteredVendorNewsletter.php';
require_once __DIR__ . '/RFQ.php';
require_once __DIR__ . '/SurplusStock.php';
require_once __DIR__ . '/SynerBaySocialIcon.php';
require_once __DIR__ . '/HintsAndTips.php';
require_once __DIR__ . '/B2BCrowdfunding.php';
// ez nincs használva, csak előkészítve
require_once __DIR__ . '/WeeklyAnalytics.php';

new CompleteYourCatalogue();
new HowToReachCustomers();
new InviteYourPartners();
new RecommendedOffers();
new RecommendedProducts();
new RegisteredVendorNewsletter();
new RFQ();
new SurplusStock();
new SynerBaySocialIcon();
new HintsAndTips();
new B2BCrowdfunding();
// egyelőre nincs használva, mert nem tudjuk, hogy kell kinyerni az adatokat
//new WeeklyAnalytics();