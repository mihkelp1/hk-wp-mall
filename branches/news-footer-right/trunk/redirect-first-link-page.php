<?php
/*
 * Template Name: Esimesele lingile postituses
 * Description: Suunab pealehelt esimesele leitud lingile postituses
 */
 
require_once( 'simple_html_dom.php' );

if (have_posts()) {
  while (have_posts()) {
    the_post();
   	$html = str_get_html( $post->post_content );
    if ( $html ) {
    	$first_link = $html->find('a', 0);
    	if ( $first_link ) {
    		wp_redirect($first_link->href);
    	} else {
    		//If no link found, display page as single page
			//header
			get_header();
			//body
			get_template_part( 'content', 'page' );
			//add footer
			get_footer();
    	}
    } else {
    	//If no html parsed, display page as single page
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