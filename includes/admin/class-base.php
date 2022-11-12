<?php
/**
 * Admin Base
 *
 * @package WC_HK_Post
 */

namespace WebStoreGuru\HK_Post_Calc\Admin;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( '\\WebStoreGuru\\HK_Post_Calc\\Admin\\Base' ) ) {

	/**
	 * Class Admin Base
	 */
	class Base {

		/**
		 * Init
		 */
		public function init() {
			add_filter( 'plugin_action_links_' . HK_POST_CALC_BASE, array( $this, 'action_links' ), 10, 4 );
			add_filter( 'plugin_row_meta', array( $this, 'row_meta' ), 10, 4 );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
		}

		/**
		 * Action links
		 *
		 * @param array  $actions Actions.
		 * @param string $plugin_file Plugin file.
		 * @param string $plugin_data Plugin data.
		 * @param string $context Context.
		 */
		public function action_links( $actions, $plugin_file, $plugin_data, $context ) {
			$settings_link = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=wc-settings&tab=shipping&section=hk_post' ) ) . '">Settings</a>';
			$actions       = array_merge( array( $settings_link ), $actions );

			return $actions;
		}

		/**
		 * Row meta
		 *
		 * @param array   $links_array Links array.
		 * @param string  $plugin_file_name Plugin file name.
		 * @param string  $plugin_data Plugin data.
		 * @param boolean $status Status.
		 */
		public function row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {
			if ( false !== strpos( $plugin_file_name, HK_POST_CALC_BASE ) ) {
				$links_array[] = '<a target="_blank" href="https://wordpress.org/support/plugin/shipping-rates-for-hk-post/">Support</a>';
			}

			return $links_array;
		}

	}
}
