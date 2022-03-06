<?php
namespace WebStoreGuru\HK_Post_Calc\Admin;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( '\\WebStoreGuru\\HK_Post_Calc\\Admin\\Base' ) ) {

    class Base {

        public function __construct() {

            add_filter( 'plugin_action_links_' . HK_POST_CALC_BASE, [ $this, 'action_links' ], 10, 4 );
            add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 4 );
            add_action( 'plugins_loaded', [ $this, 'init' ] );
        }

        public function init() {}

        public function action_links( $actions, $plugin_file, $plugin_data, $context ) {
            $settings_link = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=wc-settings&tab=shipping&section=hk_post') ) . '">Settings</a>';
            $actions = array_merge( [ $settings_link ], $actions );
        
            return $actions;
        }

        public function row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {
            if ( false !== strpos( $plugin_file_name, HK_POST_CALC_BASE ) ) {
                $links_array[] = '<a target="_blank" href="https://wordpress.org/support/plugin/shipping-rates-for-hk-post/">Support</a>';
            }

            return $links_array;
        }

    }
}