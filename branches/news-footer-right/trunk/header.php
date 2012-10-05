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
	?></title>

<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700|Ubuntu+Condensed&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href="<?php bloginfo('rss2_url'); ?>" rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' )?>" />
<link rel="stylesheet" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
<style type="text/css">
	input {
		display:none;
	}
</style>
<![endif]-->

<!--[if lte IE 8]>
<link rel="stylesheet" media="screen" href="<?php bloginfo('template_directory'); ?>/styles_ie8.css ?>" />
<![endif]-->

<!--[if IE 7]>
<link rel="stylesheet" media="screen" href="<?php bloginfo('template_directory'); ?>/styles_ie7.css ?>" />
<script type="text/javascript">
is_ie7 = true; /* Override is_ie7 variable in landing page JS file */
</script>
<![endif]-->

<?php
	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>

<!--[if lte IE 7]>
<script type="text/javascript">
	is_ie7 = true; /* Override is_ie7 variable in landing page JS file */
</script>
<![endif]-->


</head>

<body <?php body_class(); ?>>

<div id="main-container">
	<header class="page-header">
	<a href="<?php echo home_url(); ?>"><img src="<?php echo getFileURL( '/images/college-logo.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a>
		<nav id="header-menu">
			<?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'container_class' => 'header-menu-container', 'fallback_cb' => false, 'walker' => new isDraftMenuWalker()  ) ); ?>
		</nav>
		<div id="rollout-searchform" style="display:none;">
			<?php get_search_form(); ?>
		</div>
	</header>
	
	<div id="page-content">
	
		<?php if ( !is_home() ) { ?>
			<?php wp_nav_menu( array( 'theme_location' => 'nav-menu', 'container_class' => 'nav-menu-container', 'fallback_cb' => false, 'walker' => new isDraftMenuWalker()  ) ); ?>
		<?php } ?>