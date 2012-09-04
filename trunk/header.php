<?php
/**
 * The Header for our theme.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		echo " | $site_description";
	}
	?></title>

<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700|Ubuntu+Condensed&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link rel="stylesheet" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
<style type="text/css">
	input {
		display:none;
	}
</style>
<![endif]-->

<!--[if IE 8]>
<link rel="stylesheet" media="screen" href="<?php bloginfo('template_directory'); ?>/styles_ie8.css ?>" />
<![endif]-->

<!--[if IE 7]>
<link rel="stylesheet" media="screen" href="<?php bloginfo('template_directory'); ?>/styles_ie7.css ?>" />
<![endif]-->

<?php
	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>

<div id="main-container">
	<header class="page-header">
	<a href="<?php echo home_url(); ?>"><img src="<?php echo getFileURL( '/images/college-logo.png' ); ?>" alt="TLÜ Haapsalu Kolledž" title="TLÜ Haapsalu Kolledž" /></a>
		<nav id="header-menu">
			<?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'container_class' => 'header-menu-container' ) ); ?>
		</nav>
		<div id="rollout-searchform" style="display:none;">
			<?php get_search_form(); ?>
		</div>
	</header>
	
	<div id="page-content">
	
		<?php if ( !is_home() && !is_page_template( 'landing-page.php' ) ) { ?>
			<?php wp_nav_menu( array( 'theme_location' => 'nav-menu', 'container_class' => 'nav-menu-container' ) ); ?>
		<?php } ?>