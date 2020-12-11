<?php

namespace TierPricingTable\Admin;

use  TierPricingTable\Admin\Import\WPAllImport ;
use  TierPricingTable\Admin\ProductManagers\ProductManager ;
use  TierPricingTable\Freemius ;
use  TierPricingTable\Settings\Settings ;
use  TierPricingTable\TierPricingTablePlugin ;
use  Premmerce\SDK\V2\FileManager\FileManager ;
use  TierPricingTable\Admin\ProductManagers\SimpleProductManager ;
use  TierPricingTable\Admin\ProductManagers\VariationProductManager ;
use  TierPricingTable\Admin\Export\Woocommerce as WooCommerceExport ;
use  TierPricingTable\Admin\Import\Woocommerce as WooCommerceImport ;
/**
 * Class Admin
 *
 * @package TierPricingTable\Admin
 */
class Admin
{
    /**
     * @var FileManager
     */
    private  $fileManager ;
    /**
     * @var Settings
     */
    private  $settings ;
    /**
     * @var array
     */
    private  $managers ;
    /**
     * @var Freemius
     */
    private  $licence ;
    /**
     * Admin constructor.
     *
     * Register menu items and handlers
     *
     * @param FileManager $fileManager
     * @param Freemius $licence
     * @param Settings $settings
     */
    public function __construct( FileManager $fileManager, Freemius $licence, Settings $settings )
    {
        $this->fileManager = $fileManager;
        $this->settings = $settings;
        $this->licence = $licence;
        $this->initManagers();
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueAssets' ] );
        if ( get_transient( 'tier_pricing_table_activated' ) ) {
            add_action( 'admin_notices', [ $this, 'showActivationMessage' ] );
        }
        
        if ( $this->licence->isFree() ) {
            $template = 'upgrade-alert.php';
            add_action( 'woocommerce_settings_' . Settings::SETTINGS_PAGE, function () use( $template ) {
                $upgradeUrl = tpt_fs()->get_upgrade_url();
                if ( !tpt_fs()->is_registered() && !tpt_fs()->is_anonymous() ) {
                    $upgradeUrl = tpt_fs()->get_activation_url();
                }
                $this->fileManager->includeTemplate( 'admin/alerts/' . $template, [
                    'upgradeUrl'   => $upgradeUrl,
                    'contactUsUrl' => $this->licence->getContactUsPageUrl(),
                ] );
                $this->fileManager->includeTemplate( 'admin/only-in-premium-script.php' );
            } );
        }
    
    }
    
    /**
     * Init Managers
     */
    public function initManagers()
    {
        $this->managers = [
            ProductManager::class          => new ProductManager( $this->fileManager, $this->settings, $this->licence ),
            VariationProductManager::class => new VariationProductManager( $this->fileManager, $this->settings, $this->licence ),
            WooCommerceExport::class       => new WooCommerceExport(),
            WooCommerceImport::class       => new WooCommerceImport(),
            WPAllImport::class             => new WPAllImport(),
        ];
    }
    
    /**
     * Show message about activation plugin and advise next step
     */
    public function showActivationMessage()
    {
        $link = $this->settings->getLink();
        $this->fileManager->includeTemplate( 'admin/alerts/activation-alert.php', [
            'link' => $link,
        ] );
        delete_transient( 'tiered_pricing_table_activated' );
    }
    
    /**
     * Register assets on product create/update page
     *
     * @param $page
     */
    public function enqueueAssets( $page )
    {
        global  $post, $page ;
        
        if ( isset( $_GET['tab'] ) && $_GET['tab'] == Settings::SETTINGS_PAGE || ($page == 'post.php' || ($page = 'post-new.php')) && $post && $post->post_type == 'product' ) {
            wp_enqueue_script(
                'tier-pricing-table-admin-js',
                $this->fileManager->locateAsset( 'admin/main.js' ),
                [ 'jquery' ],
                TierPricingTablePlugin::VERSION
            );
            wp_enqueue_style(
                'tier-pricing-table-admin-css',
                $this->fileManager->locateAsset( 'admin/style.css' ),
                null,
                TierPricingTablePlugin::VERSION
            );
        }
    
    }

}