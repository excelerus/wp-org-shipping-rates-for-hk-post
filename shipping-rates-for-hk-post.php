<?php
/**
 * Plugin Name:       Shipping Rates for HK Post
 * Description:       Hongkong Post postage calculator.
 * Plugin URI:        https://webstoreguru.com/products/plugins/hongkong-post-postage-calculator/
 * Version:           2.2.3
 * Author:            WebStoreGuru
 * Author URI:        https://webstoreguru.com/
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html

 * Requires PHP:      7.2
 * Requires at least: 5.0
 * Tested up to:      6.3
 *
 * WC requires at least: 4.0
 * WC tested up to:      8.0
 *
 * @package WC_HK_Post
 */

defined( 'ABSPATH' ) || exit;

/**
 * Constants
 */
defined( 'HK_POST_CALC_BASE' ) || define( 'HK_POST_CALC_BASE', plugin_basename( __FILE__ ) );
defined( 'HK_POST_CALC_DIR' ) || define( 'HK_POST_CALC_DIR', plugin_dir_path( __FILE__ ) );
defined( 'HK_POST_CALC_URL' ) || define( 'HK_POST_CALC_URL', plugin_dir_url( __FILE__ ) );

require_once HK_POST_CALC_DIR . 'autoload.php';

/**
 * Activate
 */
function activate_hk_post_calc() {
	\WebStoreGuru\HK_Post_Calc\Base::activate();
}
register_activation_hook( __FILE__, 'activate_hk_post_calc' );

/**
 * Deactivation
 */
function deactivate_hk_post_calc() {
	\WebStoreGuru\HK_Post_Calc\Base::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_hk_post_calc' );

/**
 * Initialize
 */
function init_hk_post_calc() {
	$plugin = new \WebStoreGuru\HK_Post_Calc\Base();
	$plugin->init();

	if ( is_admin() ) {
		$plugin_admin = new \WebStoreGuru\HK_Post_Calc\Admin\Base();
		$plugin_admin->init();
	}
}
add_action( 'plugins_loaded', 'init_hk_post_calc' );

// HPOS Compat
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
