<?php
/**
 * Plugin Name: SynerBay
 * Description: Offer apply, invite, RFQ
 * Version: 1.0
 * Author: Kristóf Nagy
 */
namespace SynerBay;
session_start();

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
        add_action('init', [$this, 'registerMySession'], 1);
        add_action('wp_logout', [$this, 'myEndSession']);
        add_action('wp_login', [$this, 'myEndSession']);
        // basic init ...
        include_once __DIR__ . '/backend/initPlugin.php';
    }


    public function loadScript() {
        // toast - notification.js
        wp_enqueue_style('custom-toast-style', get_stylesheet_directory_uri() . '/assets/toast/notification_js/dist/notification.min.css');
        wp_enqueue_script("custom-toast-script", get_stylesheet_directory_uri() . '/assets/toast/notification_js/src/Notification.js');
        // load daypilot-modal-2.9.js
        wp_register_script( "custom-modal-script", get_stylesheet_directory_uri() . '/assets/daypilot-modal-2.9.js');

        // load daypilot-modal-2.9.js
        wp_enqueue_script("custom-modal-script", get_stylesheet_directory_uri() . '/assets/daypilot-modal-2.9.js', ['jquery'], false, true);

        // load synerbay.js
        wp_register_script( "synerbay-script", get_stylesheet_directory_uri() . '/assets/synerbay.js');

        // load synerbay.js
        wp_enqueue_script("synerbay-script", get_stylesheet_directory_uri() . '/assets/synerbay.js', ['jquery'], false, true);

        // localize the script to your domain name, so that you can reference the url to admin-ajax.php file easily
        wp_localize_script( 'synerbay-script', 'synerbayAjax', array(
            'restURL' => rest_url(),
            'restNonce' => wp_create_nonce('wp_rest')
        ));
    }

    public function registerMySession()
    {
        if(!session_id())
        {
            session_start();
        }
    }

    public function myEndSession()
    {
        session_destroy();
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

include __DIR__ . '/routing.php';
