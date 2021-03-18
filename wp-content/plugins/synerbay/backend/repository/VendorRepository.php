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
     * @param array $searchParams
     * @param bool $withMeta
     * @return array|object|null
     */
    public function getVendors(array $searchParams = [], bool $withMeta = true)
    {
        $vendors = (new UserRepository())->search($searchParams);

        if ($withMeta) {
            $vendors = $this->addUserMetaDataForVendors($vendors);
        }

        return $vendors;
    }

    /**
     * Visszaad minden adatot a felhasználóhoz (email marketinghez van használva)
     *
     * @param string|null $registeredDate
     * @return array
     */
    public function getActiveVendorsByRegisteredDate(string $registeredDate = null): array
    {
        $userSearchParams = [];

        if (!is_null($registeredDate)) {
            $userSearchParams['registered_date'] = $registeredDate;
        }

        $vendors = (new UserRepository())->search($userSearchParams);

        return $this->addUserMetaDataForVendors($vendors);
    }

    /**
     * @param array $vendors
     * @return array
     */
    public function addUserMetaDataForVendors(array $vendors = []): array
    {
        if (count($vendors)) {

            global $wpdb;

            $ret = [];

            foreach ($vendors as $vendor) {
                $ret[$vendor['ID']] = array_merge($vendor, ['data' => []]);
            }

            $vendorMetaSelect = "select * from sb_usermeta where user_id in (".implode(',', array_column($vendors, 'ID')).")";

            $vendorMetaDatas = $wpdb->get_results($vendorMetaSelect, ARRAY_A);

            foreach($vendorMetaDatas as $meta) {
                $data = $meta['meta_value'];

                if ($meta['meta_key'] == 'dokan_profile_settings') {
                    $data = unserialize($data);
                }

                $ret[$meta['user_id']]['data'][$meta['meta_key']] = $data;
            }

            $vendors = $ret;
            unset($ret);
        }

        return $vendors;
    }

    protected function prepareQuery(array $searchAttributes = [])
    {
        // TODO: Implement prepareQuery() method.
    }

    protected function getBaseTableName(): string
    {
        return 'users';
    }
}