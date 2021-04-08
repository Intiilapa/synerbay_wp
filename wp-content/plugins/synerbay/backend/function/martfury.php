<?php
// a default functiont megtalálod a wp-content/themes/martfury/inc/functions/header.php ugyanezzel a függvény névvel
function martfury_extra_search_form() {
    $search_text = martfury_get_option( 'custom_search_text' );
    $button_text = martfury_get_option( 'custom_search_button' );

    $cats_text = 'Offer';
    $defaultFormAction = "/offers";
    $defaultParamName = "query";
    $defaultParamMethod = "get";
    $cat = '
        <select onchange="synerbay.processGlobalSearchInput(this)" id="header-search-product-cat" class="product-cat-dd">
            <option class="level-0" data-rewrite="'.$defaultFormAction.'" data-param="'.$defaultParamName.'" data-method="'.$defaultParamMethod.'" selected="selected">Offer</option>
            <option class="level-1" data-rewrite="/shop" data-param="s" data-method="get">Product</option>
            <option class="level-2" data-rewrite="/stores" data-param="query" data-method="get">Store</option>
        </select>
    ';

    $item_class     = empty( $cat ) ? 'no-cats' : '';
    $post_type_html = '';

    return sprintf(
        '<form id="global-search-form" class="products-search" method="%s" action="%s">
                <div class="psearch-content">
                    <div class="product-cat"><div class="product-cat-label %s">%s</div> %s</div>
                    <div class="search-wrapper">
                        <input type="hidden" name="site-search-nonce" value="%s">
                        <input id="global-search-input" type="text" name="%s" class="search-field" autocomplete="off" placeholder="%s">
                        %s
                        <div class="search-results woocommerce"></div>
                    </div>
                    <button type="submit" class="search-submit">%s</button>
                </div>
            </form>',
        $defaultParamMethod,
        $defaultFormAction,
        esc_attr( $item_class ),
        esc_html( $cats_text ),
        $cat,
        generate_header_nonce(),
        $defaultParamName,
        esc_html( $search_text ),
        $post_type_html,
        wp_kses( $button_text, wp_kses_allowed_html( 'post' ) )
    );

}

// header invite button
if ( ! function_exists( 'martfury_header_bar' ) ) :
    function martfury_header_bar() {
        if ( ! intval( martfury_get_option( 'header_bar' ) ) ) {
            return;
        }

        ?>
        <div class="header-bar topbar">
            <?php
            $sidebar = 'header-bar';
            if ( is_active_sidebar( $sidebar ) ) {
                dynamic_sidebar( $sidebar );
            }

            do_action('synerbay_synerBayInviteButton');

//            if (get_current_user_id()) {
                do_action('synerbay_headerCreateOfferButton');
//            }
            ?>
        </div>
        <?php
    }
endif;
