<?php


namespace SynerBay\Pages\Shop;

use SynerBay\Pages\AbstractPage;
use SynerBay\Repository\UserRepository;

class Store extends AbstractPage
{
    public function storeSearch()
    {
        global $sellers;
        global $searchParameters;
        $searchParameters = wp_unslash($_GET);

        $executeSearch = global_nonced_user() || (isset($searchParameters['site-search-nonce']) ? check_header_nonce($searchParameters['site-search-nonce']) : check_store_search_nonce($searchParameters['store-site-search']));

        if (isset($searchParameters['clear'])) {
            $searchParameters = [];
        }

        if ($executeSearch) {
            $page = array_key_exists('page', $searchParameters) ? $searchParameters['page'] : 1;

            $searchParameters['only_verificated'] = true;
            $sellers = ['users' => (new UserRepository())->paginate((array)$searchParameters, 24, (int)$page, OBJECT)];

            $searchParameters['store-site-search'] = generate_store_search_nonce();
        } else {
            $sellers = [];
        }
    }

    protected function init()
    {
        parent::init();
        $this->addAction('storeSearch');
    }
}