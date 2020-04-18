<?php
/**
 * File
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_HK_Post_Shipping_Method' ) ) {
	/**
	 * Class
	 *
	 */
	class WC_HK_Post_Shipping_Method extends WC_Shipping_Method {

		/**
		 * Constructor
		 *
		 * @access public
		 * @return void
		 */
		public function __construct( $instance_id = 0 ) {

			$this->id                 = 'hk_post';
			$this->instance_id        = absint( $instance_id );
			$this->method_title       = __( 'Hongkong Post', 'shipping-rates-for-hk-post' );
			$this->method_description = __( 'Hongkong Post shipping services for domestic (SmartPost - Mail Delivery) & international (iMail) delivery services', 'shipping-rates-for-hk-post' );
			$this->supports           = [ 'shipping-zones', 'instance-settings' ];
			$this->title              = 'Hongkong Post';

			$this->instance_form_fields = [
				'enabled' => [
					'title'   => __( 'Enable / Disable', 'shipping-rates-for-hk-post' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Hongkong Post shipping method', 'shipping-rates-for-hk-post' ),
					'default' => 'yes'
				]
			];

			$this->enabled = $this->get_option( 'enabled' );
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		/**
		 * Is Available
		 *
		 * @access public
		 * @return boolean
		 */
		public function is_available( $package = [] ) {

			// Hongkong Post is only available for package originating in Hong Kong
			if ( 'HK' !== WC()->countries->get_base_country() ) return false;

			// If package weight is 0, disable
			if ( 0 === WC()->cart->get_cart_contents_weight() ) return false;

			// If any item has weight 0, disable
			// foreach( $package['contents'] as $item_id => $item ) {
			// 	$_product = $item['data'];
			// 	if ( 0 == $_product->get_weight() ) return false;
			// }

			return true;
		}

		/**
		 * Calculate Shipping Rate(s)
		 *
		 * @access public
		 * @param mixed $package
		 * @return void
		 */
		public function calculate_shipping( $package = [] ) {

			$package_weight_in_kg = wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );

			// Local SmartPost ( Mail Delivery) Service
			if ( 'HK' === $package['destination']['country'] ) {
				$postage = 0;

				$json_file = HK_POST_DATA . 'postageRate-local-SMP.json';
				$json_string = file_get_contents( $json_file );
				$json_data = json_decode( $json_string , true);

				foreach( $json_data['data'] as $service ) {
					if ( "Smart Post (Mail Delivery)" !== $service['serviceNameEN'] ) continue;

					$postage = $service['postage'][0];
					foreach( $postage['weightStep'] as $weight_step ) {
						$max_weight = (float) $weight_step['weightTo'];
						if ( $package_weight_in_kg > $max_weight ) continue;

						$min_weight = (float) $weight_step['weightFrom'];
						if ( $package_weight_in_kg < $min_weight ) continue;

						$postage = (float)$weight_step['amount'];
					}
				}

				if ( 0 != $postage ) {
					$this->add_rate( [
						'id'    => $this->id,
						'label' => 'SmartPost delivery by ' . $this->title,
						'cost'  => $postage,
					] );

					return;
				}
			}

			// International iMail Service
			$json_file = HK_POST_DATA . 'postageRate-intl-bulk-IML.json';
			$json_string = file_get_contents( $json_file );
			$json_data = json_decode( $json_string , true);

			$postage = 0;
			foreach( $json_data['data'][0]['postage'] as $destination ){

				if ( $destination['destinationCode'] !== $package['destination']['country'] ) continue;

				$postage_rate = (float) $destination['amount'];
				$handling_fee = (float) $destination['handleFee'];

				$number_of_packages = (int) ceil( $package_weight_in_kg / 2 );
				$postage = (float) ( ceil( $package_weight_in_kg ) * $postage_rate ) + ( $handling_fee * $number_of_packages );

				if ( 0 != $postage ) {
					$this->add_rate([
						'id' => $this->id . $this->instance_id,
						'label' => 'iMail by ' . $this->title,
						'cost' => $postage,
					]);
				}
			}

			// Any Other Services?
		}
	}
}
