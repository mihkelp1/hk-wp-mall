<?php
/**
 * The main template file.
 */

get_header(); ?>

<?php

if ( is_home() ) {
	get_template_part( 'content', 'home' );
}

?>

<?php get_footer(); ?>