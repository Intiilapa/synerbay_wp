<?php

namespace SynerBay\Cron\Offer;

use SynerBay\Cron\AbstractCron;
use SynerBay\Cron\InterfaceCron;
use SynerBay\Model\Offer;
use SynerBay\Repository\OfferRepository;
use SynerBay\Resource\Offer\FullOfferResource;

class Ended extends AbstractCron implements InterfaceCron
{
    public function init()
    {
        add_action('offer_ended_task', [$this, 'run']);
    }

    public function run()
    {
        // init
        $repository = new OfferRepository();
        $resource = new FullOfferResource();

        // adatlekérés
        $offers = $resource->collection($repository->search(['ended' => true, 'status' => [Offer::STATUS_PENDING, Offer::STATUS_STARTED]]));

        // van adat?
        if (count($offers)) {

            // iterálás
            foreach ($offers as $offer) {
                // offer zárása
                if ($repository->changeStatus($offer['id'], Offer::STATUS_CLOSED)) {
                    // customer-ök kiértesítése
                    if (count($offer['applies'])) {
//                        $mail =
                        foreach ($offer['applies'] as $apply) {

                        }
                    }
                    // vendor kiértesítése (ha nem járt sikerrel [vagy kevesen vannak, vagy konkrétan 0 ember jelentkezett], akkor segítsünk neki, hogy hívja meg az eddig partnereit, stb)
                    // charge (ha nem 0)
                }
            }
        }
    }
}