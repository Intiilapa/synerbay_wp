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

    /**
     * Visszaad minden adatot a felhasználóhoz (email marketinghez van használva)
     *
     * @param string|null $registeredDate
     * @return array
     */
    public function getActiveVendorsByRegisteredDate(string $registeredDate = null)
    {
        global $wpdb;

        $ret = [];

        $vendorSelect = "select
                   su.id,
                   su.user_registered, 
                    su.user_status, 
                    su.display_name,
                    su.user_email
            from
                 sb_users as su";

        if (!is_null($registeredDate)) {
            $vendorSelect .= " where date(su.user_registered) = '".$registeredDate."'";
        }

        $vendors = $wpdb->get_results($vendorSelect, ARRAY_A);

        if (count($vendors)) {
            foreach ($vendors as $vendor) {
                $ret[$vendor['id']] = array_merge($vendor, ['data' => []]);
            }

            $vendorMetaSelect = "select * from sb_usermeta where user_id in (".implode(',', array_column($vendors, 'id')).")";

            $vendorMetaDatas = $wpdb->get_results($vendorMetaSelect, ARRAY_A);

            foreach($vendorMetaDatas as $meta) {
                $data = $meta['meta_value'];

                if ($meta['meta_key'] == 'dokan_profile_settings') {
                    $data = unserialize($data);
                }

                $ret[$meta['user_id']]['data'][$meta['meta_key']] = $data;
            }
        }

        return $ret;
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