<?php
/**
 * The template for displaying 404 pages (Not Found).
 */

get_header(); ?>

<div class="main-content content-404">

	<div class="page-left-menu">
		<?php wp_nav_menu( array( 'theme_location' => 'last-resort-menu', 'container_class' => 'last-resort-menu-container', 'fallback_cb' => false, 'walker' => new isDraftMenuWalker() ) ); ?>
	</div>
	<div class="page-inside-content">
		<article id="post-0" class="post error404 not-found">
			<header class="entry-header">
				<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'hk-wp-mall' ); ?></h1>
			</header>
	
			<div class="entry-content">
				<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links, can help.', 'hk-wp-mall' ); ?></p>
	
				<?php get_search_form(); ?>
				<div class="widget-404">
					<?php the_widget( 'WP_Widget_Recent_Posts', array( 'title' =>  __('Recent news', 'hk-wp-mall'), 'number' => 10 ), array('before_title' => '<h2 class="entry-title">', 'after_title' => '</h2>') ); ?>
					<?php the_widget( 'WP_Widget_Tag_Cloud', array( 'title' =>  __('Tags', 'hk-wp-mall') ), array('before_title' => '<h2 class="entry-title">', 'after_title' => '</h2>' ) ); ?>
				</div>
	
			</div><!-- .entry-content -->
		</article><!-- #post-0 -->
	</div>

</div><!-- #content -->

<?php get_footer(); ?>