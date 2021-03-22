<?php


namespace SynerBay\Repository;


class UserRepository extends AbstractRepository
{
    protected function prepareQuery(array $searchAttributes = [])
    {
        if (!empty($searchAttributes['user_id'])) {
            $ids = $searchAttributes['user_id'];

            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $this->addWhereParameter($this->getBaseTable() . '.ID in ('.$this->buildInPlaceholderFromArrayToWhere($ids).')', $ids);
        }

        if (!empty($searchAttributes['registered_date'])) {
            $this->addWhereParameter('date(' . $this->getBaseTable() . '.user_registered) = %s', $searchAttributes['registered_date']);
        }

        if (!empty($searchAttributes['registered_date_greater_equal'])) {
            $this->addWhereParameter('date(' . $this->getBaseTable() . '.user_registered) >= %s', $searchAttributes['registered_date']);
        }

        if (!empty($searchAttributes['user_nicename'])) {
            $this->addWhereParameter($this->getBaseTable() . '.user_nicename = %s', $searchAttributes['user_nicename']);
        }

        if (!empty($searchAttributes['except_ids'])) {
            $ids = $searchAttributes['except_ids'];

            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $this->addWhereParameter($this->getBaseTable() . '.ID not in ('.$this->buildInPlaceholderFromArrayToWhere($ids).')', $ids);
        }

        if (!empty($searchAttributes['industry'])) {
            $this->addJoin('left join sb_usermeta su on ' . $this->getBaseTable() . '.ID = su.user_id and su.meta_key = "dokan_profile_settings"');
            $this->addWhereParameter('su.meta_value like %s', "%vendor_industry%" . $searchAttributes['industry'] . "%");
        }
    }

    protected function getBaseTableName(): string
    {
        return 'users';
    }
}