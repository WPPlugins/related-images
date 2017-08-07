<?php
/*
Plugin Name: Related Images
Plugin URI: http://www.fosseus.se
Description: Relate and position one or more images to a post. Ever wanted to relate images and display images och various positions to a post? This is the plugin for you.
Author: johannesfosseus
Author URI: http://profiles.wordpress.org/johannesfosseus/
Version: 2.0b
*/

/**
 * Constants
 */
define( 'RI_VERSION', '2' );
define( 'RI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'RI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RI_TPL_DIR', RI_PLUGIN_DIR.'tpl/' );


/**
 * Include required classes
 */
require( 'inc/class-config.php' );
require( 'inc/class-tpl.php' );

if( is_admin() ) {
	require( 'inc/class-backend.php' );
} else {
	require( 'inc/class-frontend.php' );
}