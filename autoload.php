<?php
defined( 'ABSPATH' ) || exit;

spl_autoload_register( 'hk_post_calc_autoloader' );

function hk_post_calc_autoloader( $class_name ) {

    $vendor_namespace  = 'WebStoreGuru';
    $classes_subfolder = 'includes';

    if ( false !== strpos( $class_name, $vendor_namespace ) ) {

        $classes_dir       = HK_POST_CALC_DIR . DIRECTORY_SEPARATOR . $classes_subfolder . DIRECTORY_SEPARATOR;
        $project_namespace = $vendor_namespace . '\HK_Post_Calc';

        $length     = strlen( $project_namespace );
        $class_file = substr( $class_name, $length );
        $class_file = str_replace( '_', '-', strtolower( $class_file ) );

        $class_parts = explode( '\\', $class_file );
        $last_index  = count( $class_parts ) - 1;
        $class_parts[ $last_index ] = 'class-' . $class_parts[ $last_index ];

        $class_file = implode( DIRECTORY_SEPARATOR, $class_parts ) . '.php';
        $location = $classes_dir . $class_file;

        if ( ! is_file( $location ) ) return;

        require_once $location;
    }

}
