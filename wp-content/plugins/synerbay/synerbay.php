<?php
/**
 * Plugin Name: SynerBay
 * Description: Offer apply, invite, RFQ
 * Version: 1.0
 * Author: Kristóf Nagy
 */

namespace SynerBay;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class SynerBay {
    /**
     * Loading all dependencies
     * @return void
     */
    public function load() {
        add_action( 'wp_enqueue_scripts', [$this, 'loadScript']);
        // basic init ...
        include_once __DIR__ . '/backend/initPlugin.php';
        /** offer aloldal generálás */
        $this->initSubPages();;
    }

    public function initSubPages()
    {
        add_action('init',  [$this, 'subPageRulesInit'], 10, 0);
        //we'll call it a template but point to your controller
        add_filter('template_include', [$this, 'includeRuleControllers'], 99);
    }

    public function subPageRulesInit(){
        add_rewrite_tag('%offer_id%', '([^&]+)', 'offer_id=');
        add_rewrite_rule('^(offer)/([^/]*)/?', 'index.php?offer_id=$matches[2]', 'top');
    }

    function includeRuleControllers($template){
        global $wp_query;
//        print '<pre>';
//        var_dump($wp_query->query_vars);die;
        /**
         * TODO nagyon gagyi, megoldandó
         */
        if (array_key_exists('name', $wp_query->query_vars)) {
            $queryVars = $wp_query->query_vars;

            if ($queryVars['name'] == 'offer') {
                $new_template = get_theme_file_path().'/pages/offerSubPage.php';
                if(file_exists($new_template)){
                    do_action('synerbay_init_global_offer_by_id', $queryVars['page']);
                    global $offer;
                    if ($offer && count($offer)) {
                        $template = $new_template;
                    }
                }
            }
        }
//        if(get_query_var('offer_id')){
//            //path to your template file
//            $new_template = get_theme_file_path().'/pages/offerSubPage.php';
//            if(file_exists($new_template)){
//                do_action('synerbay_init_global_offer_by_id', get_query_var('page'));
//                $template = $new_template;
//            }
//        }

        return $template;
    }


    public function loadScript() {
        // load synerbay.js
        wp_register_script( "synerbay-script", get_stylesheet_directory_uri() . '/synerbay.js');

        // load synerbay.js
        wp_enqueue_script("synerbay-script", get_stylesheet_directory_uri() . '/synerbay.js', ['jquery'], false, true);

        // localize the script to your domain name, so that you can reference the url to admin-ajax.php file easily
        wp_localize_script( 'synerbay-script', 'synerbayAjax', array(
            'restURL' => rest_url(),
            'restNonce' => wp_create_nonce('wp_rest')
        ));
    }
}

/**
 * Loading my plugin
 * @return void
 */
function synerBayLoad() {
    $pg = new SynerBay();
    $pg->load();
}

// We need to call the function with the namespace
add_action('plugins_loaded', 'SynerBay\synerBayLoad');
