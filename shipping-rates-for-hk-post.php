<?php
/**
 * Plugin Name:       Shipping Rates for HK Post
 * Plugin URI:        https://github.com/excelerus/wp-org-shipping-rates-for-hk-post/
 * Description:       Get shipping rates from Hongkong Post API for accurate and realtime postage charges to international as well as domestic destinations.
 * Version:           1.1.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            EXCELERUS
 * Author URI:        https://www.excelerus.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * 
 * WC requires at least: 4.0
 * WC tested up to:      4.8
 */

namespace EXCELERUS\WooCommerce\Shipping\HKPost;

defined( 'ABSPATH' ) || exit;

// Constants
defined( 'HK_POST_VERSION' ) || define( 'HK_POST_VERSION', '1.0.1' );
defined( 'HK_POST_BASE' ) || define( 'HK_POST_BASE', plugin_basename( __FILE__ ) );
defined( 'HK_POST_DIR' ) || define( 'HK_POST_DIR', plugin_dir_path( __FILE__) );
defined( 'HK_POST_URL' ) || define( 'HK_POST_URL', plugin_dir_url( __FILE__) );

// Activation
function activate_plugin() {
    if ( ! current_user_can( 'activate_plugins' ) ) return;

    global $wp_version;
    $php = '7.0';
    $wp  = '5.0';

    if ( version_compare( PHP_VERSION, $php, '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) );

        add_action( 'admin_notices', function() {
            ?>
            <div class="error">
                <p><?php sprintf( __( 'Shipping Rates for Hongkong Post is not compatible with your current PHP version %1$s. Upgrade your PHP to alteast %2$s to use this plugin.', 'shipping-rates-for-hk-post' ), PHP_VERSION, $php ); ?></p>
            </div>
            <?php
        } );
    }

    if ( version_compare( $wp_version, $wp, '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) );
        
        add_action( 'admin_notices', function() {
            ?>
            <div class="error">
                <p><?php sprintf( __( 'Shipping Rates for Hongkong Post is not compatible with your current WordPress version %1$s. Upgrade your WordPress to alteast %2$s to use this plugin.', 'shipping-rates-for-hk-post' ), $wp_version, $wp ); ?></p>
            </div>
            <?php
        } );
    }

    set_transient( 'plugin-name-admin-notice-on-activation', true, 5 );
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\activate_plugin' );

// Deactivation
function deactivate_plugin() {
    if ( ! current_user_can( 'activate_plugins' ) ) return;
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate_plugin' );

// Action links
function add_action_links( $actions ) {
    $settings_link = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=wc-settings&tab=shipping&section=hk_post') ) . '">Settings</a>';
    $actions = array_merge( [ $settings_link ], $actions );

    return $actions;
}
add_filter( 'plugin_action_links_' . HK_POST_BASE, __NAMESPACE__ . '\add_action_links' );

// Row Meta links
function add_row_meta( $links, $plugin_file ) {
    if ( strpos( $plugin_file, basename(__FILE__) ) ) {
        $links[] = '<a target="_blank" href="https://wordpress.org/support/plugin/shipping-rates-for-hk-post/">Support</a>';
    }

    return $links;
}
add_filter( 'plugin_row_meta', __NAMESPACE__ . '\add_row_meta', 10, 2 );

// Load plugin if WooCommerce is active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    // Init
    add_action( 'woocommerce_shipping_init', function() {
        require_once 'includes/class-wc-shipping-hk-post.php';
    } );

    // Add Shipping Method
    add_filter( 'woocommerce_shipping_methods', function( $methods ) {
        $methods[ 'hk_post' ] = 'WC_Shipping_HK_Post';
		return $methods;
    } );

    // Integration - WOMC
    add_filter( 'hkpost_postage', function( $amount ) {
        if ( class_exists( '\WOOMC\App' ) ) {
            $user = \WOOMC\App::instance()->getUser();

            $currency_detector = new \WOOMC\Currency\Detector();
            $rate_storage = new \WOOMC\Rate\Storage();
            $price_rounder = new \WOOMC\Price\Rounder();
            $price_calculator = new \WOOMC\Price\Calculator( $rate_storage, $price_rounder );

            $to = $currency_detector->currency();
            $from = $currency_detector->getDefaultCurrency();

            return $price_calculator->calculate( (float) $amount, $to, $from );
        }
        return $amount;
    } );

}
