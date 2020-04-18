<?php
/**
 * Plugin Name:     Shipping Rates for HK Post
 * Plugin URI:      https://github.com/excelerus/wp-org-shipping-rates-for-hk-post/
 * Description:     Get shipping rates from Hongkong Post API for accurate and realtime postage charges to international as well as domestic destinations.
 * Version:         1.0.0
 * Author:          EXCELERUS
 * Author URI:      https://www.excelerus.com/
 * Developer:       EXCELERUS
 * Developer URI:   https://www.excelerus.com/
 *
 * WC requires at least:
 * WC tested up to:
 *
 * License:     GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

define( 'HK_POST_DATA', __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR );

/**
 * Plugin Activation
 */
function activate_shipping_rates_for_hk_post() {
	// Activation
}
register_activation_hook( __FILE__, 'activate_shipping_rates_for_hk_post' );

/**
 * Plugin Deactivation
 */
function deactivate_shipping_rates_for_hk_post() {
	// Deactivate
}
register_deactivation_hook( __FILE__, 'deactivate_shipping_rates_for_hk_post' );

// Run only if WooCommerce is active
$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {

	/**
	 * Init Shipping Method
	 */
	function init_shipping_rates_for_hk_post_shipping_method() {
		require_once 'includes/class-wc-hk-post-shipping-method.php';
	}
	add_action( 'woocommerce_shipping_init', 'init_shipping_rates_for_hk_post_shipping_method' );

	/**
	 * Add Shipping Method
	 */
	function add_shipping_rates_for_hk_post_shipping_method( $methods ) {
		$methods[ 'hk_post' ] = 'WC_HK_Post_Shipping_Method';
		return $methods;
	}
	add_filter( 'woocommerce_shipping_methods', 'add_shipping_rates_for_hk_post_shipping_method' );

} else {
	add_action( 'admin_notices', 'shipping_rates_for_hk_post_admin_notice_woocommerce_dependency_check_error' );
}

/**
 * Admin Notice for WooCommerce dependency check error
 */
function shipping_rates_for_hk_post_admin_notice_woocommerce_dependency_check_error() {
	$plugin_data = get_plugin_data( __FILE__ );
	?>
	<div class="notice notice-error is-dismissible">
		<p><strong><?php echo $plugin_data['Name'] . esc_html__( ' requries WooCommerce to be installed and activated.', 'shipping-rates-for-hk-post' ); ?></strong></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text">Dismiss this notice.</span>
		</button>
	</div>
	<?php
}
