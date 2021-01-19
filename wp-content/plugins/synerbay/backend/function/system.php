<?php

if ( ! function_exists( 'generate_header_nonce' ) ) {
    function generate_header_nonce()
    {
        if (!array_key_exists('header_nonce_action', $_SESSION)) {
            $_SESSION['header_nonce_action'] = 'header_' . time() .'_'. rand(1, 1000);
        }

        return wp_create_nonce($_SESSION['header_nonce_action']);
    }
}

if ( ! function_exists( 'check_header_nonce' ) ) {
    function check_header_nonce($nonce)
    {
        if (array_key_exists('nonced-user', $_SESSION) && $_SESSION['nonced-user']) {
            return true;
        }

        if (!array_key_exists('header_nonce_action', $_SESSION)) {
            return false;
        } else {
            $valid = wp_verify_nonce($nonce, $_SESSION['header_nonce_action']);
            unset($_SESSION['header_nonce_action']);

            if ($valid) {
                $_SESSION['nonced-user'] = true;
            }

            return $valid;
        }
    }
}

if ( ! function_exists( 'generate_offer_search_nonce' ) ) {
    function generate_offer_search_nonce()
    {
        if (array_key_exists('nonced-user', $_SESSION) && $_SESSION['nonced-user']) {
            return true;
        }

        if (!array_key_exists('generate_offer_search_nonce_action', $_SESSION)) {
            $_SESSION['generate_offer_search_nonce_action'] = 'offer_search_' . time() .'_'. rand(1, 1000);
        }

        return wp_create_nonce($_SESSION['generate_offer_search_nonce_action']);
    }
}

if ( ! function_exists( 'check_offer_search_nonce' ) ) {
    function check_offer_search_nonce($nonce)
    {
        if (!array_key_exists('generate_offer_search_nonce_action', $_SESSION)) {
            return false;
        } else {
            $valid = wp_verify_nonce($nonce, $_SESSION['generate_offer_search_nonce_action']);
            unset($_SESSION['generate_offer_search_nonce_action']);

            if ($valid) {
                $_SESSION['nonced-user'] = true;
            }

            return $valid;
        }
    }
}

if ( ! function_exists( 'global_nonced_user' ) ) {
    function global_nonced_user()
    {
        return $_SESSION['nonced-user'];
    }
}
