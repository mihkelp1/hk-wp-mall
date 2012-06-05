<?php
/**
 * The Header for our theme.
 */
 
?>

<html>
<head>
<title>0.001v</title>
</head>

<body>

<?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'container_class' => 'header-menu-container' ) ); ?>

<?php wp_nav_menu( array( 'theme_location' => 'nav-menu', 'container_class' => 'nav-menu-container' ) ); ?>