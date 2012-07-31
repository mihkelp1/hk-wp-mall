<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

<div class="main-content content-news">
	<div class="page-left-menu">
		<?php
			the_widget('WP_Widget_Calendar');
			the_widget( 'WP_Widget_Archives', array('count' => 0 , 'dropdown' => 0, 'title' => ' ' ) );
			the_widget( 'WP_Widget_Tag_Cloud', array( 'title' => ' ' ) );
			
		?>
	</div>
	<div class="page-inside-content">
		<?php while ( have_posts() ) : the_post(); ?>
			<nav id="nav-single">
				<span class="nav-previous"><?php previous_post_link(); ?></span>
				<span class="nav-next"><?php next_post_link(); ?></span>
			</nav><!-- #nav-single -->

			<?php 
				get_template_part( 'content', 'single' ); 
			?>

		<?php endwhile; // end of the loop. ?>
	</div>
</div><!-- #content -->
<?php get_footer(); ?>