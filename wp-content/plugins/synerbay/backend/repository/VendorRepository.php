<?php


namespace SynerBay\Repository;


class VendorRepository extends AbstractRepository
{
    public function getActiveVendorsForSelect()
    {
        global $wpdb;

        return $wpdb->get_results("select
                    sb_usermeta.user_id,
                    sb_usermeta.meta_value as 'store_name'
                from
                     sb_usermeta
                left join sb_offers on sb_usermeta.user_id = sb_offers.user_id
                where
                sb_offers.offer_end_date >= '".date('Y-m-d H:i:s')."'
                and
                sb_usermeta.meta_key = 'dokan_store_name'
                group by sb_usermeta.user_id
                order by sb_usermeta.user_id asc",
            ARRAY_A);
    }

    public function getFollowers(int $vendorID)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "select follower_id"
                . " from {$wpdb->prefix}dokan_follow_store_followers"
                . " where vendor_id = %d"
                . "     and unfollowed_at is null",
                $vendorID,
            ),
            ARRAY_A
        );
    }

    protected function prepareQuery(array $searchAttributes = [])
    {
        // TODO: Implement prepareQuery() method.
    }

    protected function getBaseTableName()
    {
        return 'users';
    }
}