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
			'footer-menu-right' => __('Footer right'),
			'last-resort-menu' => __('General menu') )
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

add_action( 'after_setup_theme', '_initTheme' );

/**
 * Register and load some JavaScript
 */

function loadAndRegisterJavaScripts() {
	wp_enqueue_script( 'jquery' );
	
	wp_register_script( 'jquery-easing', get_template_directory_uri().'/js/jquery.bxSlider/jquery.easing.1.3.js' );
	wp_register_script( 'jquery-bxSlider', get_template_directory_uri().'/js/jquery.bxSlider/jquery.bxSlider.min.js' );
	wp_register_script( 'jquery-overflow', get_template_directory_uri().'/js/jquery.hoverflow.min.js');
	
	wp_enqueue_script( 'jquery-easing' );
	wp_enqueue_script( 'jquery-bxSlider' );
	wp_enqueue_script( 'jquery-overflow' );
	wp_enqueue_script( 'thickbox' );
	
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
	wp_enqueue_style( 'thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css' );
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

/**
 * Output Archive left menu
 */
 
function getArchiveLeftMenu() {
	the_widget('WP_Widget_Calendar');
	the_widget( 'WP_Widget_Archives', array('count' => 0 , 'dropdown' => 0, 'title' => ' ' ) );
	the_widget( 'WP_Widget_Tag_Cloud', array( 'title' => ' ' ) );
}


/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own twentyeleven_posted_on to override in a child theme
 *
 * @since Twenty Eleven 1.0
 */
 
function _posted_on() {
	printf( __( '<div class="posted-on"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a></div>', 'hk-wp-mall' ),
		esc_url( get_day_link( get_the_date('Y'), get_the_date('m'), get_the_date('d') ) ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
}

function getAllNavMenus(){
    return get_terms( 'nav_menu', array( 'hide_empty' => true ) );
}

/** Some metabox stuff */

add_action( 'add_meta_boxes', 'configurePostMetaboxes' );

function configurePostMetaboxes() {
	if ( editorInLandingPage() ) {
		add_meta_box('landing-page-metabox', __( 'Landing page', 'hk-wp-mall' ),  'landingPageMetabox', 'page', 'normal', 'high');
	}
}

function replace_thickbox_text($translated_text, $text, $domain) {
	if ('Insert into Post' == $text) {
		$referer = strpos( wp_get_referer(), 'hk-landing-page' );
		if ( $referer != '' ) {
			return __('Add to landing page', 'hk-wp-mall' );
		}
	}
	return $translated_text;
}

add_action( 'admin_init', 'landingPageImageSetup' );

function landingPageImageSetup() {
	global $pagenow;
	if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
		// Now we'll replace the 'Insert into Post Button' inside Thickbox
		add_filter( 'gettext', 'replace_thickbox_text'  , 1, 3 );
	}
}

function landingPageMetabox() {
	//TODO HERE WILL BE YOUTUBE VIDEO EMBED, SAIS LINK, MENU SELECTION AND REMINDER FEATURE
	echo '<p><label for="landing-yt-video" />'.__( 'Youtube URL', 'hp-wp-mall' ).'</label> ';
	echo '<input type="text" name="landing-yt-video" id="landing-yt-video" style="width: 400px" value="'.getLandingPageYT().'"/></p>';
	
	echo '<p>';
	echo '<label for="landing-nav-menu">'.__( 'Pick a landing page menu', 'hk-wp-mall').'</label> ';
	echo '<select name="landing-nav-menu" id="landing-nav-menu">';
	echo '<option value="0">'.__( 'Disabled', 'hk-wp-mall' ).'</option>';
	$prev_menu_id = getLandingPageMenu( get_the_ID(), true );
	foreach( getAllNavMenus() as $nav_menu ) {
		if ( $prev_menu_id == $nav_menu->term_id ) {
			echo '<option value="'.$nav_menu->term_id.'" selected="selected">'.$nav_menu->name.'</option>';
		} else {
			echo '<option value="'.$nav_menu->term_id.'">'.$nav_menu->name.'</option>';
		}
	}
	echo '</select>';
	echo '</p>';
	?>
	<script type="text/javascript">
				jQuery(document).ready(function($) {
				$('#upload_logo_button').click(function() {
					tb_show('Vali maandumislehe pilt', 'media-upload.php?referer=hk-landing-page&type=image&TB_iframe=true&post_id=0', false);
					return false;
				});			
				window.send_to_editor = function(html) {
					var html = $(html);
					var image = $(html);
					if ( html.prop('tagName') != 'IMG' ) {
						image = html.find('img:first');
					}
					var image_url = image.attr('src');
					var image_id = image.attr('class').match(/\d+/gi);
					$('#imgURL').html(html);
					$('#landing-image-id').val(image_id);
					if ( image.length > 0 ) {
						$('#upload_logo_button').addClass('hide-if-landing-thumbnail');
						$('#remove_landing_thumbnail').removeClass('hide-if-landing-thumbnail');
					}
					tb_remove();
				}
				
				$('#remove_landing_thumbnail').click(function() {
					if ( confirm("Oled kindel?") ) {
						$('#imgURL').html("");
						$('#landing-image-id').val(0);
						$('#upload_logo_button').removeClass('hide-if-landing-thumbnail');
						$(this).addClass('hide-if-landing-thumbnail');
					}
				});
			});

		</script>
		<style type="text/css">
			.hide-if-landing-thumbnail {
				display:none;
			}
		</style>
		<p id="imgURL">
			<?php
				if ( has_landing_thumbnail() ) {
					the_landing_thumbnail( 'full' );
				}
			?>
		</p>
		
		<a id="upload_logo_button" title="Vali maandumislehe pilt" class="<?php echo ( has_landing_thumbnail() ? 'hide-if-landing-thumbnail' : ''); ?>">Vali maandumislehe pilt</a>
		<a id="remove_landing_thumbnail" title="Eemalda maandumislehe pilt" class="<?php echo ( has_landing_thumbnail() ? '' : 'hide-if-landing-thumbnail'); ?>">Eemalda pilt</a>
		
		<input type="hidden" name="landing-image-id" value="<?php echo the_landing_thumbnail_ID(); ?>" id="landing-image-id" />
		
	<?php
}

add_action( 'save_post', 'savePostMetadata', 1, 2 );

function savePostMetadata( $post_id, $post ) {
	if ( $post->post_type == 'page' ) {
		if ( $post->page_template == 'landing-page.php' ) {
			update_post_meta( $post_id, 'landing-nav-menu', sanitize_text_field( $_POST['landing-nav-menu'] ) );
			update_post_meta( $post_id, 'landing-yt-video', sanitize_text_field( $_POST['landing-yt-video'] ) );
			update_post_meta( $post_id, 'landing-image-id', sanitize_text_field( $_POST['landing-image-id'] ) );
		}
	}
}

function getLandingPageMenu( $page_id, $id_only = false ) {
	$menu_id = get_post_meta( $page_id, 'landing-nav-menu', true );
	if ( $id_only ) {
		return $menu_id;
	}
	if ( $menu_id ) {
		wp_nav_menu( array( 'menu' => $menu_id, 'container_class' => 'landing-nav-menu-wrapper', 'menu_class' => 'landing-page-menu' ) );
	}
}

function getLandingPageYT( $id_only = false ) {
	if ( $id_only ) {
		$query_params = parse_url( get_post_meta( get_the_ID(), 'landing-yt-video', true ), PHP_URL_QUERY );
		if ( !is_null( $query_params ) ) {
			$params_array = explode( '&', $query_params );
			foreach( $params_array as $param ) {
				$query_pair = explode( '=', $param );
				if ( $query_pair[0] == 'v' ) {
					return $query_pair[1];
				}
			}
		}
		return false;
	} else {
		return get_post_meta( get_the_ID(), 'landing-yt-video', true );
	}
}

function has_youtube_video() {
	return getLandingPageYT( true ) ? true : false;
}

function the_landing_thumbnail_ID() {
	return get_post_meta( get_the_ID(), 'landing-image-id', true );
}

function the_landing_thumbnail( $size = 'medium' ) {
	echo wp_get_attachment_image( the_landing_thumbnail_ID(), $size, false, array( 'class' => 'wp-post-image landing-image' ) );
}

function has_landing_thumbnail() {
	return the_landing_thumbnail_ID() ?  true : false;
}

function editorInLandingPage() {
	$data = explode( '/', get_page_template() );
	return array_search( 'landing-page.php', $data ) ? true : false;
}


add_action( 'admin_init', 'removePostMetaboxes' );

function removePostMetaboxes() {
    remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
    remove_meta_box( 'authordiv', 'post', 'normal' );
    remove_meta_box( 'postcustom', 'post', 'normal' );
	remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
	remove_meta_box( 'commentsdiv', 'post', 'normal' );
    remove_meta_box( 'formatdiv', 'post', 'normal' );
    
    remove_meta_box( 'commentsdiv', 'page', 'normal' );
    remove_meta_box( 'authordiv', 'page', 'normal' );
    remove_meta_box( 'postcustom', 'page', 'normal' );
    remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
    
}

?>