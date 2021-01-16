<?php


namespace SynerBay\Pages\Shop;


use SynerBay\Helper\RouteHelper;
use SynerBay\Pages\AbstractPage;
use SynerBay\Repository\OfferRepository;
use SynerBay\Resource\Offer\FullOfferResource;

class Offer extends AbstractPage
{
    protected function init()
    {
        parent::init();
        $this->addAction('init_global_offer_sub_page', 'subPage');
        $this->addAction('init_dashboard_offer_sub_page', 'dashboardShowPage');
        $this->addAction('editOfferSubPage');
        $this->addAction('offerSearch');
    }

    public function subPage($offerID) {
        global $offer;
        do_action('synerbay_init_global_offer_by_id', (int)$offerID);

        if (!$offer || !count($offer)) {
            $this->page404();
        }
    }

    public function dashboardShowPage($offerID) {
        global $offer;
        do_action('synerbay_init_global_offer_by_id', (int)$offerID);

        if (!$offer || !count($offer) || $offer['user_id'] != get_current_user_id()) {
            $this->page404();
        }
    }

    public function editOfferSubPage(int $offerID) {
        global $offer;
        do_action('synerbay_init_global_offer_by_id', $offerID);

        if (!$offer || !count($offer) || $offer['user_id'] != get_current_user_id()) {
            $this->page404();
        }

        // dokan hook
        add_action('dokan_load_custom_template', [$this, 'dokanCustomTemplateUpdateOffer']);
    }

    public function offerSearch() {
        global $offers;
        global $searchParameters;
        $searchParameters = $_GET;
        $searchParameters['visible'] = true;
        $searchParameters['except_ended'] = true;
        $page = array_key_exists('page', $searchParameters) ? $searchParameters['page'] : 1;
//        var_dump($searchParameters);
        $offers = (new FullOfferResource())->collection((new OfferRepository())->paginate((array)$searchParameters, 25, (int)$page));
    }

    /**
     * custom template, csak akkor hívjuk be, ha ...
     */
    public function dokanCustomTemplateUpdateOffer()
    {
        require_once RouteHelper::getRoute('edit_offer')->get_template();
    }
}