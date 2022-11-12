<?php
/**
 * Shipping Method
 *
 * @package WC_HK_Post
 */

namespace WebStoreGuru\HK_Post_Calc;

defined( 'ABSPATH' ) || die;

if ( ! class_exists( '\\WebStoreGuru\\HK_Post_Calc\\Shipping_Method' ) ) {

	/**
	 * Shipping Method class
	 */
	class Shipping_Method extends \WC_Shipping_Method {

		/**
		 * Services array
		 *
		 * @var array
		 */
		private $services;

		/**
		 * Constructor
		 *
		 * @param int $instance_id Instance Id.
		 */
		public function __construct( $instance_id = 0 ) {
			$this->id                 = 'hk_post';
			$this->title              = __( 'HK Post', 'shipping-rates-for-hk-post' );
			$this->instance_id        = absint( $instance_id );
			$this->method_title       = __( 'Hongkong Post', 'shipping-rates-for-hk-post' );
			$this->method_description = __( 'Postage calculator', 'shipping-rates-for-hk-post' );
			$this->supports           = array(
				'settings',
				'shipping-zones',
				'instance-settings',
				'instance-settings-modal',
			);

			$this->services = include HK_POST_CALC_DIR . '/services.php';
			$this->init();
			$this->init_settings();

			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		/**
		 * Init
		 */
		public function init() {
			$this->form_fields = array(
				'delivery_services' => array(
					'title'       => __( 'Enable Delivery Services', 'shipping-rates-for-hk-post' ),
					'type'        => 'title',
					'description' => '',
				),
			);

			foreach ( $this->services as $key => $service ) {
				$this->form_fields[ $key ] = array(
					'type'     => 'checkbox',
					'label'    => $service['label'],
					'default'  => true,
					'desc_tip' => false,
				);
			}
		}

		/**
		 * Admin Options
		 */
		public function admin_options() {
			if ( $this->is_enabled() ) {
				parent::admin_options();
			} else {
				?>
				<div class="inline error">
					<p>
						<strong><?php esc_html_e( 'Shipping Method disabled', 'shipping-rates-for-hk-post' ); ?></strong>: <?php esc_html_e( 'Hongkong Post can only be used for store with address in Hong Kong.', 'shipping-rates-for-hk-post' ); ?>
					</p>
				</div>
				<?php
			}
		}

		/**
		 * Is Enabled
		 */
		public function is_enabled() {
			$enabled = true;
			if ( 'HK' !== WC()->countries->get_base_country() ) {
				$enabled = false;
			}

			return $enabled;
		}

		/**
		 * Is Available?
		 *
		 * @param array $package Package.
		 */
		public function is_available( $package ) {
			$available = true;

			if ( 0 == WC()->cart->get_cart_contents_weight() ) { // phpcs:ignore
				$available = false;
			}

			return $available;
		}

		/**
		 * Sort rates by cost
		 *
		 * @param array $rates Rates.
		 * @param array $package Package.
		 */
		public function sort_rates_by_cost( $rates, $package ) {
			if ( empty( $rates ) ) {
				return;
			}
			if ( ! is_array( $rates ) ) {
				return;
			}

			uasort(
				$rates,
				function ( $a, $b ) {
					if ( $a == $b ) { // phpcs:ignore
						return 0;
					}
					return ( $a->cost < $b->cost ) ? -1 : 1;
				}
			);

			return $rates;
		}

		/**
		 * Calculate shipping
		 *
		 * @param array $package Package.
		 */
		public function calculate_shipping( $package = array() ) {

			$destination_code = $package['destination']['country'];
			$weight_in_kgs    = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );
			$postage          = 0;

			foreach ( $this->services as $key => $service ) {
				if ( 'yes' !== $this->get_option( $key ) ) {
					continue;
				}

				$postage = $this->get_postage_rate( $service, $destination_code, $weight_in_kgs );
				if ( $postage ) {
					$this->add_rate(
						array(
							'id'    => $key,
							'label' => $this->title . ' ' . $service['title'],
							'cost'  => apply_filters( 'hkpost_postage', $postage ),
						)
					);
				}
			}

			add_filter( 'woocommerce_package_rates', array( $this, 'sort_rates_by_cost' ), 10, 2 );

		}

		/**
		 * Get postage rate for destination & weight
		 *
		 * @param array  $service Service.
		 * @param string $destination Destination.
		 * @param float  $weight Weight.
		 *
		 * @return false|float
		 */
		private function get_postage_rate( $service, $destination, $weight ) {
			$file         = HK_POST_CALC_DIR . 'data/' . $service['file'];
			$file_content = file_get_contents( $file ); // phpcs:ignore
			$file_json    = json_decode( $file_content, true );
			$file_data    = $file_json['data'];

			$service_name = $service['name'];
			$service_data = null;
			foreach ( $file_data as $file_service ) {
				if ( $service_name == $file_service['serviceNameEN'] ) { // phpcs:ignore
					$service_data = $file_service;
				}
			}
			if ( ! $service_data ) {
				return false;
			}

			$rates_data = null;
			foreach ( $service_data['postage'] as $postage ) {
				if ( $postage['destinationCode'] !== $destination ) {
					continue;
				}
				$rates_data = $postage['weightStep'];
			}
			if ( ! $rates_data ) {
				return false;
			}

			$postage = 0;
			foreach ( $rates_data as $rate ) {
				if ( $weight >= $rate['weightFrom'] && $weight < $rate['weightTo'] ) {
					$postage = (float) $rate['amount'];

					// Additional Weight Rate.
					if ( isset( $rate['additionalWeight'] )
						&& isset( $rate['additionalAmount'] )
					) {
						$additional_weight  = $weight - $rate['weightFrom'];
						$additional_unit    = ceil( $additional_weight / $rate['additionalWeight'] );
						$additional_postage = $additional_unit * $rate['additionalAmount'];
						$postage           += $additional_postage;
					}
				}
			}
			if ( ! $postage ) {
				return false;
			}

			return $postage;
		}
	}

}
