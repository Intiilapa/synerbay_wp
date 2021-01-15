<?php
// a default functiont megtalálod a wp-content/themes/martfury/inc/functions/header.php ugyanezzel a függvény névvel
function martfury_extra_search_form() {
    $search_text = martfury_get_option( 'custom_search_text' );
    $button_text = martfury_get_option( 'custom_search_button' );

    $cats_text = 'Offer';
    $defaultFormAction = "/offers";
    $defaultParamName = "query";
    $cat = '
        <select onchange="synerbay.processGlobalSearchInput(this)" id="header-search-product-cat" class="product-cat-dd">
            <option class="level-0" data-rewrite="'.$defaultFormAction.'" data-param="'.$defaultParamName.'" selected="selected">Offer</option>
            <option class="level-1" data-rewrite="/shop" data-param="s">Product</option>
            <option class="level-2" data-rewrite="/store-listing=" data-param="dokan_seller_search">Store</option>
        </select>
    ';

    $item_class     = empty( $cat ) ? 'no-cats' : '';
    $post_type_html = '';

    return sprintf(
        '<form id="global-search-form" class="products-search" method="get" action="%s">
                <div class="psearch-content">
                    <div class="product-cat"><div class="product-cat-label %s">%s</div> %s</div>
                    <div class="search-wrapper">
                        <input id="global-search-input" type="text" name="%s" class="search-field" autocomplete="off" placeholder="%s">
                        %s
                        <div class="search-results woocommerce"></div>
                    </div>
                    <button type="submit" class="search-submit">%s</button>
                </div>
            </form>',
        $defaultFormAction,
        esc_attr( $item_class ),
        esc_html( $cats_text ),
        $cat,
        $defaultParamName,
        esc_html( $search_text ),
        $post_type_html,
        wp_kses( $button_text, wp_kses_allowed_html( 'post' ) )
    );

}

if ( ! function_exists( 'is_shop' ) ) {

    /**
     * Is_shop - Returns true when viewing the product type archive (shop).
     *
     * @return bool
     */
    function is_shop() {
        die('remco');
        return ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' )) );
    }
}
