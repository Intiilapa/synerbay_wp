<?php


namespace SynerBay\Repository;


class UserRepository extends AbstractRepository
{
    protected function prepareQuery(array $searchAttributes = [])
    {
        if (!empty($searchAttributes['query'])) {
            $this->addJoin('left join sb_usermeta suq on ' . $this->getBaseTable() . '.ID = suq.user_id and suq.meta_key = "dokan_store_name"');
            $this->addWhereParameter('suq.meta_value like %s', "%" . $searchAttributes['query'] . "%");
        }

        if (!empty($searchAttributes['user_id'])) {
            $ids = $searchAttributes['user_id'];

            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $this->addWhereParameter($this->getBaseTable() . '.ID in (' . $this->buildInPlaceholderFromArrayToWhere($ids) . ')', $ids);
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

            $this->addWhereParameter($this->getBaseTable() . '.ID not in (' . $this->buildInPlaceholderFromArrayToWhere($ids) . ')', $ids);
        }

        if (!empty($searchAttributes['industry'])) {
            $this->addJoin('left join sb_usermeta sui on ' . $this->getBaseTable() . '.ID = sui.user_id and sui.meta_key = "dokan_profile_settings"');
            $this->addWhereParameter('sui.meta_value like %s', "%vendor_industry%" . $searchAttributes['industry'] . "%");
        }

        if (!empty($searchAttributes['company_type'])) {
            $this->addJoin('left join sb_usermeta suct on ' . $this->getBaseTable() . '.ID = suct.user_id and suct.meta_key = "dokan_profile_settings"');
            $this->addWhereParameter('suct.meta_value like %s', "%vendor_type%" . $searchAttributes['company_type'] . "%");
        }

        if (!empty($searchAttributes['shipping_to'])) {
            $this->addJoin('left join sb_usermeta sust on ' . $this->getBaseTable() . '.ID = sust.user_id and sust.meta_key = "dokan_profile_settings"');
            $this->addWhereParameter('sust.meta_value like %s', "%vendor_shipping_to%" . $searchAttributes['shipping_to'] . "%");
        }

        if (!empty($searchAttributes['annual_revenue'])) {
            $this->addJoin('left join sb_usermeta suar on ' . $this->getBaseTable() . '.ID = suar.user_id and suar.meta_key = "dokan_profile_settings"');
            $this->addWhereParameter('suar.meta_value like %s', "%vendor_revenue%" . $searchAttributes['annual_revenue'] . "%");
        }

        if (!empty($searchAttributes['employees'])) {
            $this->addJoin('left join sb_usermeta sue on ' . $this->getBaseTable() . '.ID = sue.user_id and sue.meta_key = "dokan_profile_settings"');
            $this->addWhereParameter('sue.meta_value like %s', "%vendor_employees%" . $searchAttributes['employees'] . "%");
        }

        if (!empty($searchAttributes['product_range'])) {
            $this->addJoin('left join sb_usermeta supr on ' . $this->getBaseTable() . '.ID = supr.user_id and supr.meta_key = "dokan_profile_settings"');
            $this->addWhereParameter('supr.meta_value like %s', "%vendor_product_range%" . $searchAttributes['product_range'] . "%");
        }

        if (!empty($searchAttributes['only_verificated'])) {
            $this->addWhereParameter($this->getBaseTable() . '.ID not in (select sunf.user_id from sb_usermeta sunf where sunf.meta_key = %s)', '_dokan_email_pending_verification');
        }

        if (!empty($searchAttributes['rating'])) {

        }
    }

    protected function getBaseTableName(): string
    {
        return 'users';
    }
}