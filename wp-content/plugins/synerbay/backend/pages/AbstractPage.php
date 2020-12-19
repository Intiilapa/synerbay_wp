<?php


namespace SynerBay\Pages;


use SynerBay\Traits\WPAction;

abstract class AbstractPage
{
    use WPAction;

    public function __construct()
    {
        $this->init();
    }

    protected function page404()
    {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( '404' );
        exit();
    }

    protected function init() {}
}