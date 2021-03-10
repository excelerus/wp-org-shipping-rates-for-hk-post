<?php

require_once '/var/wp/wp-test/public/includes/functions.php';

tests_add_filter( 'muplugins_loaded', function() {

    switch_theme( 'storefront' );

    update_option( 'active_plugins', [
        'shipping-rates-for-hk-post/shipping-rates-for-hk-post.php'
    ] );
    
} );

$test_lib_bootstrap_file = '/var/wp/wp-test/public/includes/bootstrap.php';
if ( ! file_exists( $test_lib_bootstrap_file ) ) {
    echo PHP_EOL . "Error : unable to find " . $test_lib_bootstrap_file . PHP_EOL;
    exit( '' . PHP_EOL );
}
require_once '/var/wp/wp-test/public/includes/bootstrap.php';

// $current_user = new WP_User( 1 );
// $current_user->set_role( 'administrator' );

echo PHP_EOL;
echo 'Using Wordpress core : ' . ABSPATH . PHP_EOL;
echo PHP_EOL;