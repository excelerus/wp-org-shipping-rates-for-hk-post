<?php
/**
 * Base
 *
 * @package WC_HK_Post
 */

namespace WebStoreGuru\HK_Post_Calc;

defined( 'ABSPATH' ) || die;

if ( ! class_exists( '\\WebStoreGuru\\HK_Post_Calc\\Base' ) ) {

	/**
	 * Base class
	 */
	class Base {

		/**
		 * Services
		 *
		 * @var array
		 */
		private $services;

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->services = include HK_POST_CALC_DIR . '/services.php';

			add_action( 'init', array( $this, 'load_translation' ) );

			add_filter( 'woocommerce_shipping_methods', array( $this, 'add_shipping_method' ) );

			add_action( 'init', array( $this, 'action_scheduler' ) );
			add_action( 'update_rates_files', array( $this, 'update_rates_files' ) );
		}

		/**
		 * Activate
		 */
		public static function activate() {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}
		}

		/**
		 * Deactivate
		 */
		public static function deactivate() {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}
		}

		/**
		 * Load translation
		 */
		public function load_translation() {
			load_plugin_textdomain( 'shipping-rates-for-hk-post' );
		}

		/**
		 * Init
		 */
		public function init() {}

		/**
		 * Add shipping method
		 *
		 * @param array $methods Methods.
		 */
		public function add_shipping_method( $methods ) {
			$methods['hk_post'] = '\WebStoreGuru\HK_Post_Calc\Shipping_Method';
			return $methods;
		}

		/**
		 * Action scheduler
		 */
		public function action_scheduler() {
			if ( ! as_has_scheduled_action( 'update_rates_files', array(), 'hk_post' ) ) {
				as_schedule_cron_action( strtotime( 'tomorrow' ), '0 0 * * *', 'update_rates_files', array(), 'hk_post' );
			}
		}

		/**
		 * Update rate files
		 */
		public function update_rates_files() {

			foreach ( $this->services as $key => $service ) {

				$response = wp_remote_get( 'https://www.hongkongpost.hk/opendata/' . $service['file'] );
				if ( is_wp_error( $response ) ) {
					return;
				}
				if ( ! isset( $response['body'] ) ) {
					return;
				}
				$remote_file         = json_decode( $response['body'] );
				$remote_last_updated = $remote_file->lastUpdateDate; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

				$local_file         = HK_POST_CALC_DIR . 'data/' . $service['file'];
				$local_rates        = json_decode( file_get_contents( $local_file ) );
				$local_last_updated = $local_rates->lastUpdateDate; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

				if ( $remote_last_updated > $local_last_updated ) {
					file_put_contents( $local_file, $response['body'] );
				}
			}
		}
	}

}
