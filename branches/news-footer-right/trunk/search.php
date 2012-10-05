<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

<div class="main-content content-search">
	<div class="page-left-menu">
		<?php wp_nav_menu( array( 'theme_location' => 'last-resort-menu', 'container_class' => 'last-resort-menu-container', 'fallback_cb' => false, 'walker' => new isDraftMenuWalker()  ) ); ?>
	</div>
	
	<div class="page-inside-content">

		<?php if ( have_posts() ) : ?>

			<header class="search-results-header">
				<h1 class="entry-title"><?php printf( __( 'Search Results for: %s', 'hk-wp-mall' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<div class="entry-content">
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</header>


			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', 'single' );
				?>

			<?php endwhile; ?>


		<?php else : ?>

			<article id="post-0" class="post no-results not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Nothing Found', 'hk-wp-mall' ); ?></h1>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'hk-wp-mall' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		<?php endif; ?>

	</div>
</div><!-- #content -->

<?php get_footer(); ?>