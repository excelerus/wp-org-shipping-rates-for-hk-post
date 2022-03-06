<?php
namespace WebStoreGuru\HK_Post_Calc;

defined( 'ABSPATH' ) || die;

if ( ! class_exists( '\\WebStoreGuru\\HK_Post_Calc\\Base' ) ) {

    class Base {
        private $services;

        public function __construct() {
            $this->services = include HK_POST_CALC_DIR . '/services.php';

            add_action( 'init', [ $this, 'load_translation' ] );

            add_filter( 'woocommerce_shipping_methods', [ $this, 'add_shipping_method' ] );

            add_action( 'init', [ $this, 'action_scheduler' ] );
            add_action( 'update_rates_files', [ $this, 'update_rates_files' ] );
        }

        public static function activate() {
            if ( ! current_user_can( 'activate_plugins' ) ) return;
        }
    
        public static function deactivate() {
            if ( ! current_user_can( 'activate_plugins' ) ) return;
        }
    
        public function load_translation() {
            load_plugin_textdomain( 'shipping-rates-for-hk-post' );
        }

        public function init() {}

        public function add_shipping_method( $methods ) {
            $methods['hk_post'] = '\WebStoreGuru\HK_Post_Calc\Shipping_Method';
            return $methods;
        }

        public function action_scheduler() {
            if ( ! as_has_scheduled_action( 'update_rates_files', [], 'hk_post' ) ) {
                as_schedule_cron_action( strtotime( 'tomorrow' ), '0 0 * * *', 'update_rates_files', [], 'hk_post' );
            }
        }

        private function update_rates_files() {

            foreach ( $this->services as $key => $service ) {
                $response = wp_remote_get( 'https://www.hongkongpost.hk/opendata/' . $service['file'] );
                if ( is_wp_error( $response ) ) return;
                if ( ! isset( $response['body'] ) ) return;
                $remote_file         = json_decode( $response['body'] );
                $remote_last_updated = $remote_file->lastUpdateDate;
    
                $local_file  = HK_POST_CALC_DIR . '/data//' . $service['file'];
                $local_rates = json_decode( file_get_contents( $local_file ) );
                $local_last_updated = $local_rates->lastUpdateDate;
    
                if ( $remote_last_updated > $local_last_updated ) {
                    file_put_contents( $local_file, $response['body'] );
                }
            }
        }
    
    }

}
