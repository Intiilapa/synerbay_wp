<?php


namespace SynerBay\Pages;


use SynerBay\Traits\WPAction;

abstract class AbstractPage
{
    use WPAction;

    public function __construct()
    {
        $this->disableWP404();
        $this->init();
    }

    protected function disableWP404()
    {
        add_filter( 'pre_handle_404', function() {
            # you can do anything you want here but the easiest and safest is
            # wp_redirect( 'your url with query parameters from the failing 404 url' );
            # exit();
            return FALSE;
        } );
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