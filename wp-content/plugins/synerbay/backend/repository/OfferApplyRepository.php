<?php

namespace SynerBay\Repository;


class OfferApplyRepository extends AbstractRepository
{

    protected function prepareQuery(array $searchAttributes = [])
    {
        if (isset($searchAttributes['user_id'])) {
            $userID = $searchAttributes['user_id'];
            if (!is_array($userID)) {
                $userID = [$userID];
            }
            $this->addWhereParameter($this->getBaseTable() . '.user_id in (%s)', implode(', ', $userID));
        }

        if (isset($searchAttributes['offer_id'])) {
            $offerID = $searchAttributes['offer_id'];

            if (!is_array($offerID)) {
                $offerID = [$offerID];
            }

            $this->addWhereParameter($this->getBaseTable() . '.offer_id in (%s)', implode(', ', $offerID));
        }
    }

    protected function getBaseTableName()
    {
        return 'offer_applies';
    }
}