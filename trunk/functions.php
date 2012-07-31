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
 
function _initTheme() {
 	register_nav_menus(
		array( 'header-menu' => __( 'Header menu' ),
			'nav-menu' => __( 'Navigation menu' ),
			'footer-menu-left' => __('Footer left'),
			'footer-menu-middle' => __('Footer middle'),
			'footer-menu-right' => __('Footer right') )
		 );
		
	register_sidebar(array(
	  'name' => __( 'Footer sidebar' ),
	  'id' => 'footer-sidebar',
	  'before_widget' => '<div id="%1$s" class="widget %2$s">',
	  'after_widget'  => '</div>',
	  'description' => __( 'Widgets in this area will be shown in the footer.' )
	));
	
	add_theme_support( 'post-thumbnails' ); 
}

add_action( 'init', '_initTheme' );

/**
 * Register and load some JavaScript
 */

function loadAndRegisterJavaScripts() {
	wp_enqueue_script( 'jquery' );
	
	wp_register_script( 'jquery-easing', get_template_directory_uri().'/js/jquery.bxSlider/jquery.easing.1.3.js' );
	wp_register_script( 'jquery-bxSlider', get_template_directory_uri().'/js/jquery.bxSlider/jquery.bxSlider.min.js' );
	
	wp_enqueue_script( 'jquery-easing' );
	wp_enqueue_script( 'jquery-bxSlider' );
	
	//Load some styles too
	loadAndRegisterCSS();
}

add_action( 'wp_enqueue_scripts', 'loadAndRegisterJavaScripts' );

/**
 * Register and load styles
 */

function loadAndRegisterCSS() {
	wp_register_style( 'jquery.bxSlider', get_template_directory_uri(). '/js/jquery.bxSlider/bx_styles/bx_styles.css' );
	wp_enqueue_style( 'jquery.bxSlider' );
}

/**
 * Helper method for loading images
 *
 * @return String URL
 */

function getFileURL( $file_name ) {
	return get_template_directory_uri().$file_name;
}

/**
 * Get menu title/name
 *
 * @return String
 */

//If function doesn't exist
if ( !function_exists( 'wp_nav_menu_title' ) ) {
	function wp_nav_menu_title( $theme_location ) {
		$title = '';
		if ( $theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $theme_location ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $theme_location ] );
				
			if( $menu && $menu->name ) {
				$title = $menu->name;
			}
		}
	
		return apply_filters( 'wp_nav_menu_title', $title, $theme_location );
	}
}

/**
 * Build news list
 */

function getNewsSlider() {
	$args = array(
		'numberposts'     => 20,
		'orderby'         => 'post_date',
		'order'           => 'DESC',
		'post_type'       => 'post',
		'post_status'     => 'publish' );
	$posts = get_posts( $args );
	
	if ( $posts ) {
		echo '<ul id="slider1">';
		foreach( $posts as $post ) {
			$notice_class = 'news-icon';
			if ( in_category( 'teated', $post ) ) {
				$notice_class = 'notice-icon';
			}
			echo '<li><a href="'.get_permalink( $post->ID ).'" title="'.$post->post_title.'"><div class="news-image '.$notice_class.'"></div><div class="news-notice-content">'.$post->post_title.'</div></a></li>';
		}
		echo '</ul>';
	} else {
		return false;
	}
}

?>