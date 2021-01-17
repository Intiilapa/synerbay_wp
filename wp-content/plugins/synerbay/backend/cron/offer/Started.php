<?php

namespace SynerBay\Cron\Offer;

use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Emails\Service\TestEmail;
use SynerBay\Model\Offer;
use SynerBay\Repository\OfferRepository;
use SynerBay\Resource\Offer\OfferStartedResource;

class Started extends AbstractCron implements InterfaceCron
{
    public function init()
    {
        add_action('offer_started_task', [$this, 'run']);
    }

    public function run()
    {
//        $mailer = new TestEmail();
//        $mailer->send('Kristóf', 'nagy.kristof.janos@gmail.com');
//        $mailer->send('Kristóf', 'mama1612@gmail.com');

        // init
        $repository = new OfferRepository();
        $resource = new OfferStartedResource();

        // adatlekérés
        $offers = $resource->collection($repository->search(['started' => true, 'status' => [Offer::STATUS_PENDING]]));

        // van adat?
        if (count($offers)) {

            // iterálás
            foreach ($offers as $offer) {
                // offer zárása
                if ($repository->changeStatus($offer['id'], Offer::STATUS_STARTED)) {
                    // customer-ök kiértesítése
                    if (count($offer['applies'])) {
//                        $mail =
                    }
                    // vendor kiértesítése (ha nem járt sikerrel [vagy kevesen vannak, vagy konkrétan 0 ember jelentkezett], akkor segítsünk neki, hogy hívja meg az eddig partnereit, stb)
                    // charge (ha nem 0)
                }
            }
        }
    }
}