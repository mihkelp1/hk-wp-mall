<?php

/**
 * Our theme actions hooks, filters and helper methods
 *
 * By Raido Kuli 2012
 */


/**
 * Register 4 menus for using on the page
 *
 */
 
function register_my_menus() {
  register_nav_menus(
    array( 'header-menu' => __( 'Header menu' ),
    	'nav-menu' => __( 'Navigation menu' ),
    	'footer-menu-left' => __('Footer left'),
    	'footer-menu-middle' => __('Footer middle'),
    	'footer-menu-right' => __('Footer right') )
  );
}

add_action( 'init', 'register_my_menus' );

/**
 * Helper method for loading images
 *
 * @return String URL
 */

function getFileURL( $file_name ) {
	return get_template_directory_uri().$file_name;
}

?>