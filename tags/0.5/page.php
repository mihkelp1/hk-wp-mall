<?php
get_header();

while ( have_posts() ) : 
	the_post(); 
	get_template_part( 'content', 'page' );
endwhile; // end of the loop.

get_footer();
?>
