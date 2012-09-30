<?php
/*
 * Template Name: Esimesele alamlehele
 * Description: Suunab pealehelt esimesele alamlehele
 */

if (have_posts()) {
  while (have_posts()) {
    the_post();
    $pagekids = get_pages("child_of=".$post->ID."&sort_column=menu_order");
    if ( $pagekids ) {
    	$firstchild = $pagekids[0];
    	wp_redirect(get_permalink($firstchild->ID));
    } else {
    	//If no child found, display page as single page
    	//header
    	get_header();
    	//body
    	get_template_part( 'content', 'page' );
    	//add footer
    	get_footer();
    }
  }
}
?>