<?php
/**
 * Hongkong Post Shipping Method
 * 
 * Extends WC_Shipping_Method to calculate shipping rates from json files.
 * 
 * @since 1.1.0
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Shipping_HK_Post' ) ) {

    /**
     * Class
     */
    class WC_Shipping_HK_Post extends WC_Shipping_Method {

        public static $log_enabled = false;
        public static $log = false;

        /**
         * Constructor
         */
        public function __construct( $instance_id = 0 ) {
            error_log( __METHOD__ );

            $this->id                 = 'hk_post';
			$this->instance_id        = absint( $instance_id );
			$this->method_title       = __( 'Hongkong Post', 'shipping-rates-for-hk-post' );
			$this->method_description = __( 'Postage calculator', 'shipping-rates-for-hk-post' );
			$this->supports           = [ 'settings', 'instance-settings', 'shipping-zones' ];

            $this->init();

            add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

            
            if ( ! $this->is_valid_for_use() ) {
                $this->enabled = 'no';
            }
        }

        /**
         * Init
         */
        public function init() {

            // Settings
            $this->form_fields = [
                'title'           => [
                    'title'       => __( 'Title', 'shipping-rates-for-hk-post' ),
                    'type'        => 'text',
                    'description' => __( 'This controls the title which the user sees on cart page.', 'shipping-rates-for-hk-post' ),
                    'default'     => __( 'Hongkong Post', 'shipping-rates-for-hk-post' ),
                    'desc_tip'    => true,
                ],
                'debug'           => [
                    'title'       => __( 'Debug', 'shipping-rates-for-hk-post' ),
                    'type'        => 'checkbox',
                    'label'       => __( 'Enable logging', 'shipping-rates-for-hk-post' ),
                    'description' => sprintf( __( 'Log Hongkong Post events inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'shipping-rates-for-hk-post' ), '<code>' . WC_Log_Handler_File::get_log_file_path( 'hkpost' ) . '</code>' ),
                    'default'     => 'no',
                    'desc_tip'    => false,
                ]
            ];

            // Instance Settings
            $this->instance_form_fields = [
                'delivery_services' => [
                    'title'       => __( 'Enable Delivery Services', 'shipping-rates-for-hk-post' ),
                    'type'        => 'title',
                    'description' => '',
                ],
                'loc_ord' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'Local Mail (Packet)', 'shipping-rates-for-hk-post' ),
                    'default'     => false,
                    'desc_tip'    => false
                ],
                'loc_reg' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'Local Registered Mail (Packet)', 'shipping-rates-for-hk-post' ),
                    'default'     => false,
                    'desc_tip'    => false
                ],
                'loc_par' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'Local Parcels', 'shipping-rates-for-hk-post' ),
                    'default'     => false,
                    'desc_tip'    => false
                ],
                'loc_std' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'Local CourierPost', 'shipping-rates-for-hk-post' ),
                    'default'     => false,
                    'desc_tip'    => false
                ],
                'loc_smp' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'Local Smart Post (Mail Delivery)', 'shipping-rates-for-hk-post' ),
                    'default'     => true,
                    'desc_tip'    => false
                ],
                // 'sur_ord' => [
                //     'type'        => 'checkbox',
                //     'label'       => __( 'International Surface Mail (Packet)', 'shipping-rates-for-hk-post' ),
                //     'default'     => false,
                //     'desc_tip'    => false
                // ],
                'sur_reg' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'International Surface Registered Mail (Packet)', 'shipping-rates-for-hk-post' ),
                    'default'     => false,
                    'desc_tip'    => false
                ],
                // 'air_ord' => [
                //     'type'        => 'checkbox',
                //     'label'       => __( 'International Air Mail (Packet)', 'shipping-rates-for-hk-post' ),
                //     'default'     => false,
                //     'desc_tip'    => false
                // ],
                'air_reg' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'International Air Registered Mail (Packet)', 'shipping-rates-for-hk-post' ),
                    'default'     => true,
                    'desc_tip'    => false
                ],
                'sur_par' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'International Surface Parcel', 'shipping-rates-for-hk-post' ),
                    'default'     => false,
                    'desc_tip'    => false
                ],
                'air_par' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'International Air Parcel', 'shipping-rates-for-hk-post' ),
                    'default'     => false,
                    'desc_tip'    => false
                ],
                'spt_std' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'International Speedpost (Standard Service)', 'shipping-rates-for-hk-post' ),
                    'default'     => false,
                    'desc_tip'    => false
                ],
                'exp' => [
                    'type'        => 'checkbox',
                    'label'       => __( 'International e-Express', 'shipping-rates-for-hk-post' ),
                    'default'     => false,
                    'desc_tip'    => false
                ]
            ];

            $this->init_settings();

            // User Variables
            $this->title       = $this->get_option( 'title' );
            $this->debug       = 'yes' === $this->get_option( 'debug' );
            self::$log_enabled = $this->debug;
        }

        /**
         * Logging method.
         *
         * @param string $message Log message.
         * @param string $level Optional. Default 'info'. Possible values:
         *                      emergency|alert|critical|error|warning|notice|info|debug.
         */
        public static function log( $message, $level = 'info' ) {
            if ( self::$log_enabled ) {
                if ( empty( self::$log ) ) {
                    self::$log = wc_get_logger();
                }
                self::$log->log( $level, $message, array( 'source' => 'hkpost' ) );
            }
        }

        /**
         * Processes and saves options.
         * If there is an error thrown, will continue to save and validate fields, but will leave the erroring field out.
         *
         * @return bool was anything saved?
         */
        public function process_admin_options(){
            $saved = parent::process_admin_options();

            // Maybe clear logs.
            if ( 'yes' !== $this->get_option( 'debug', 'no' ) ) {
                if ( empty( self::$log ) ) {
                    self::$log = wc_get_logger();
                }
                self::$log->clear( 'hkpost' );
            }

            return $saved;
        }

        /**
         * Check if this shipping carrier is available in the store location.
         *
         * @return bool
         */
        public function is_valid_for_use() {
            return 'HK' === WC()->countries->get_base_country();
        }

        /**
         * Admin Panel Options.
         */
        public function admin_options() {
            if ( $this->is_valid_for_use() ) {
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
         * Calculate Shipping
         */
        public function calculate_shipping( $package = [] ) {

            if ( ! ( 0 < WC()->cart->get_cart_contents_weight() ) ) {
                // WC_Shipping_HK_Post::log( 'Weight of contents in cart is ' . WC()->cart->get_cart_contents_weight() , 'debug' );
                return;
            }

            if ( 'yes' === $this->get_option( 'loc_ord') ) $this->calculate_shipping_loc_ord( $package );
            if ( 'yes' === $this->get_option( 'loc_reg') ) $this->calculate_shipping_loc_reg( $package );
            if ( 'yes' === $this->get_option( 'loc_par') ) $this->calculate_shipping_loc_par( $package );
            if ( 'yes' === $this->get_option( 'loc_std') ) $this->calculate_shipping_loc_std( $package );
            if ( 'yes' === $this->get_option( 'loc_smp') ) $this->calculate_shipping_loc_smp( $package );
            // if ( 'yes' === $this->get_option( 'sur_ord') ) $this->calculate_shipping_sur_ord( $package );
            if ( 'yes' === $this->get_option( 'sur_reg') ) $this->calculate_shipping_sur_reg( $package );
            // if ( 'yes' === $this->get_option( 'air_ord') ) $this->calculate_shipping_air_ord( $package );
            if ( 'yes' === $this->get_option( 'air_reg') ) $this->calculate_shipping_air_reg( $package );
            if ( 'yes' === $this->get_option( 'sur_par') ) $this->calculate_shipping_sur_par( $package );
            if ( 'yes' === $this->get_option( 'air_par') ) $this->calculate_shipping_air_par( $package );
            if ( 'yes' === $this->get_option( 'spt_std') ) $this->calculate_shipping_spt_std( $package );
            if ( 'yes' === $this->get_option( 'exp') ) $this->calculate_shipping_exp( $package );

            // Sort
            add_filter( 'woocommerce_package_rates', function( $rates, $package ) {
                if ( empty( $rates ) ) return;
                if ( ! is_array( $rates ) ) return;

                uasort( $rates, function ( $a, $b ) { 
                    if ( $a == $b ) return 0;
                    return ( $a->cost < $b->cost ) ? -1 : 1; 
                } );
                
                return $rates;
            }, 10, 2 );
        }

        public function calculate_shipping_loc_ord( $package ) {
            if ( 'HK' !== $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );
            $rates_file   = HK_POST_DIR . '/data/postageRate-local-ORD.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;
            foreach( $rates_data['data'] as $service ) {
                if ( 'Local Mail (Packet)' === $service['serviceNameEN'] ) {
                    $rates = $service['postage'][0];
                    foreach( $rates['weightStep'] as $rate ) {
                        if ( $package_kgs >= $rate['weightFrom']
                            && $package_kgs < $rate['weightTo']
                        ) $postage = $rate['amount'];
                    }
                }
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_loc_ord',
                'label' => $this->title . __( ' Local Mail', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_loc_reg( $package ) {

            if ( 'HK' !== $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );
            $rates_file   = HK_POST_DIR . '/data/postageRate-local-REG.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;
            foreach( $rates_data['data'] as $service ) {
                if ( 'Local Registered Mail (Packet)' === $service['serviceNameEN'] ) {
                    $rates = $service['postage'][0];
                    foreach( $rates['weightStep'] as $rate ) {
                        if ( $package_kgs >= $rate['weightFrom']
                            && $package_kgs < $rate['weightTo']
                        ) $postage = $rate['amount'];
                    }
                }
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_loc_reg',
                'label' => $this->title . __( ' Local Registered Mail', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_loc_par( $package ) {

            if ( 'HK' !== $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );
            $rates_file   = HK_POST_DIR . '/data/postageRate-local-PAR.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;
            $service = $rates_data['data'][0];
            $rates = $service['postage'][0];
            foreach( $rates['weightStep'] as $rate ) {
                if ( $package_kgs >= $rate['weightFrom']
                    && $package_kgs < $rate['weightTo']
                ) $postage = $rate['amount'];
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_loc_par',
                'label' => $this->title . __( ' Local Parcels', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_loc_std( $package ) {

            if ( 'HK' !== $package['destination']['country'] ) return;


            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );
            $rates_file   = HK_POST_DIR . '/data/postageRate-local-LCP.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;
            $service = $rates_data['data'][0];
            $rates = $service['postage'][0];
            foreach( $rates['weightStep'] as $rate ) {
                if ( $package_kgs >= $rate['weightFrom']
                    && $package_kgs < $rate['weightTo']
                ) $postage = $rate['amount'];
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_loc_std',
                'label' => $this->title . __( ' Local CourierPost', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_loc_smp( $package ) {

            if ( 'HK' !== $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );
            $rates_file   = HK_POST_DIR . '/data/postageRate-local-SMP.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;
            foreach( $rates_data['data'] as $service ) {
                if ( 'Smart Post (Mail Delivery)' === $service['serviceNameEN'] ) {
                    $rates = $service['postage'][0];
                    foreach( $rates['weightStep'] as $rate ) {
                        if ( $package_kgs >= $rate['weightFrom']
                            && $package_kgs < $rate['weightTo']
                        ) $postage = $rate['amount'];
                    }
                }
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_loc_smp',
                'label' => $this->title . __( ' Smart Post', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_sur_ord( $package ) {

            if ( 'HK' === $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );

            $rates_file   = HK_POST_DIR . '/data/postageRate-intl-ORD.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;

            foreach( $rates_data['data'] as $service ) {
                if ( 'Air Mail (Packet)' !== $service['serviceNameEN'] ) continue;
                $destinations = $service['postage'];
            }

            foreach( $destinations as $destination ) {
                if ( $destination['destinationCode'] !== $package['destination']['country'] ) continue;
                $rates = $destination['weightStep'];
            }
            if ( ! isset( $rates ) ) return;

            foreach( $rates as $rate ) {
                if ( $package_kgs >= $rate['weightFrom']
                    && $package_kgs < $rate['weightTo']
                ) $postage = $rate['amount'];
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_sur_ord',
                'label' => $this->title . __( ' Surface Mail', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_sur_reg( $package ) {

            if ( 'HK' === $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );

            $rates_file   = HK_POST_DIR . '/data/postageRate-intl-REG.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;

            foreach( $rates_data['data'] as $service ) {
                if ( 'Surface Registered Mail (Packet)' !== $service['serviceNameEN'] ) continue;
                $destinations = $service['postage'];
            }

            foreach( $destinations as $destination ) {
                if ( $destination['destinationCode'] !== $package['destination']['country'] ) continue;
                $rates = $destination['weightStep'];
            }
            if ( ! isset( $rates ) ) return;

            foreach( $rates as $rate ) {
                if ( $package_kgs >= $rate['weightFrom']
                    && $package_kgs < $rate['weightTo']
                ) {
                    $postage = (float) $rate['amount'];

                    if ( isset( $rate['additionalWeight'] ) && isset( $rate['additionalAmount'] ) ) {
                        $additional_weight  = $package_kgs - $rate['weightFrom'];
                        $additional_unit    = ceil( $additional_weight / $rate['additionalWeight'] );
                        $additional_postage = $additional_unit * $rate['additionalAmount'];
                        $postage = $postage + $additional_postage;
                    }

                }
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_sur_reg',
                'label' => $this->title . __( ' Surface Registered Mail', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_air_ord( $package ) {

            if ( 'HK' === $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );

            $rates_file   = HK_POST_DIR . '/data/postageRate-intl-ORD.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;

            foreach( $rates_data['data'] as $service ) {
                if ( 'Air Mail (Packet)' !== $service['serviceNameEN'] ) continue;
                $destinations = $service['postage'];
            }

            foreach( $destinations as $destination ) {
                if ( $destination['destinationCode'] !== $package['destination']['country'] ) continue;
                $rates = $destination['weightStep'];
            }
            if ( ! isset( $rates ) ) return;

            foreach( $rates as $rate ) {
                if ( $package_kgs >= $rate['weightFrom']
                    && $package_kgs < $rate['weightTo']
                ) $postage = $rate['amount'];
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_air_ord',
                'label' => $this->title . __( ' Air Mail', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_air_reg( $package ) {

            if ( 'HK' === $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );

            $rates_file   = HK_POST_DIR . '/data/postageRate-intl-REG.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;

            foreach( $rates_data['data'] as $service ) {
                if ( 'Air Registered Mail (Packet)' !== $service['serviceNameEN'] ) continue;
                $destinations = $service['postage'];
            }

            foreach( $destinations as $destination ) {
                if ( $destination['destinationCode'] !== $package['destination']['country'] ) continue;
                $rates = $destination['weightStep'];
            }
            if ( ! isset( $rates ) ) return;

            foreach( $rates as $rate ) {
                if ( $package_kgs >= $rate['weightFrom']
                    && $package_kgs < $rate['weightTo']
                ) {
                    $postage = (float) $rate['amount'];

                    if ( isset( $rate['additionalWeight'] ) && isset( $rate['additionalAmount'] ) ) {
                        $additional_weight  = $package_kgs - $rate['weightFrom'];
                        $additional_unit    = ceil( $additional_weight / $rate['additionalWeight'] );
                        $additional_postage = $additional_unit * $rate['additionalAmount'];
                        $postage = $postage + $additional_postage;
                    }

                }
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_air_reg',
                'label' => $this->title . __( ' Air Registered Mail', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }
    
        public function calculate_shipping_sur_par( $package ) {

            if ( 'HK' === $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );

            $rates_file   = HK_POST_DIR . '/data/postageRate-intl-SURPAR.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;

            foreach( $rates_data['data'] as $service ) {
                if ( 'Surface Parcel' !== $service['serviceNameEN'] ) continue;
                $destinations = $service['postage'];
            }

            foreach( $destinations as $destination ) {
                if ( $destination['destinationCode'] !== $package['destination']['country'] ) continue;
                $rates = $destination['weightStep'];
            }
            if ( ! isset( $rates ) ) return;

            foreach( $rates as $rate ) {
                if ( $package_kgs >= $rate['weightFrom']
                    && $package_kgs < $rate['weightTo']
                ) {
                    $postage = (float) $rate['amount'];

                    if ( isset( $rate['additionalWeight'] ) && isset( $rate['additionalAmount'] ) ) {
                        $additional_weight  = $package_kgs - $rate['weightFrom'];
                        $additional_unit    = ceil( $additional_weight / $rate['additionalWeight'] );
                        $additional_postage = $additional_unit * $rate['additionalAmount'];
                        $postage = $postage + $additional_postage;
                    }

                }
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_sur_par',
                'label' => $this->title . __( ' Surface Parcel', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_air_par( $package ) {

            if ( 'HK' === $package['destination']['country'] ) return;

            $package_kgs = wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );

            $rates_file   = HK_POST_DIR . '/data/postageRate-intl-AIRPAR.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;

            foreach( $rates_data['data'] as $service ) {
                if ( 'Air Parcel' !== $service['serviceNameEN'] ) continue;
                $destinations = $service['postage'];
            }

            foreach( $destinations as $destination ) {
                if ( $destination['destinationCode'] !== $package['destination']['country'] ) continue;
                $rates = $destination['weightStep'];
            }
            if ( ! isset( $rates ) ) return;

            foreach( $rates as $rate ) {
                if ( $package_kgs >= $rate['weightFrom']
                    && $package_kgs < $rate['weightTo']
                ) {
                    $postage = (float) $rate['amount'];

                    if ( isset( $rate['additionalWeight'] ) && isset( $rate['additionalAmount'] ) ) {
                        $additional_weight  = $package_kgs - $rate['weightFrom'];
                        $additional_unit    = ceil( $additional_weight / $rate['additionalWeight'] );
                        $additional_postage = $additional_unit * $rate['additionalAmount'];
                        $postage = $postage + $additional_postage;
                    }
                }
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_air_par',
                'label' => $this->title . __( ' Air Parcel', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_spt_std( $package ) {

            if ( 'HK' === $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );

            $rates_file   = HK_POST_DIR . '/data/postageRate-intl-SPT.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;

            foreach( $rates_data['data'] as $service ) {
                if ( 'Speedpost (Standard Service)' !== $service['serviceNameEN'] ) continue;
                $destinations = $service['postage'];
            }

            foreach( $destinations as $destination ) {
                if ( $destination['destinationCode'] !== $package['destination']['country'] ) continue;
                $rates = $destination['weightStep'];
            }
            if ( ! isset( $rates ) ) return;

            foreach( $rates as $rate ) {
                if ( $package_kgs >= $rate['weightFrom']
                    && $package_kgs < $rate['weightTo']
                ) $postage = $rate['amount'];
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_spt_std',
                'label' => $this->title . __( ' Speedpost', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

        public function calculate_shipping_exp( $package ) {

            if ( 'HK' === $package['destination']['country'] ) return;

            $package_kgs = (float) wc_get_weight( WC()->cart->get_cart_contents_weight(), 'kgs' );

            $rates_file   = HK_POST_DIR . '/data/postageRate-intl-EXP.json';
            $rates_string = file_get_contents( $rates_file );
            $rates_data   = json_decode( $rates_string, true );

            $postage = 0;

            foreach( $rates_data['data'] as $service ) {
                if ( 'e-Express Service' !== $service['serviceNameEN'] ) continue;
                $destinations = $service['postage'];
            }

            foreach( $destinations as $destination ) {
                if ( $destination['destinationCode'] !== $package['destination']['country'] ) continue;
                $rates = $destination['weightStep'];
            }
            if ( ! isset( $rates ) ) return;

            foreach( $rates as $rate ) {
                if ( $package_kgs >= $rate['weightFrom']
                    && $package_kgs < $rate['weightTo']
                ) {
                    $postage = (float) $rate['amount'];

                    if ( isset( $rate['additionalWeight'] ) && isset( $rate['additionalAmount'] ) ) {
                        $additional_weight  = $package_kgs - $rate['weightFrom'];
                        $additional_unit    = ceil( $additional_weight / $rate['additionalWeight'] );
                        $additional_postage = $additional_unit * $rate['additionalAmount'];
                        $postage = $postage + $additional_postage;
                    }

                }
            }

            if ( 0 == $postage ) return; // no rates found

            $this->add_rate([
                'id'    => 'hkp_exp',
                'label' => $this->title . __( ' e-Express Service', 'shipping-rates-for-hk-post' ),
                'cost'  => apply_filters( 'hkpost_postage', $postage )
            ]);
        }

    }
}