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

<link rel="stylesheet" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
<style type="text/css">
	input {
		display:none;
	}
</style>
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
	<header>
	<img src="<?php echo getFileURL( '/images/college-logo.png' ); ?>" alt="TLÜ Haapsalu Kolledž" title="TLÜ Haapsalu Kolledž" />
		<nav id="header-menu"><?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'container_class' => 'header-menu-container' ) ); ?></nav>
		<?php if ( is_singular() ) { ?>
			<?php wp_nav_menu( array( 'theme_location' => 'nav-menu', 'container_class' => 'nav-menu-container' ) ); ?>
		<?php } ?>
	</header>
	
	<div id="page-content">
		tere