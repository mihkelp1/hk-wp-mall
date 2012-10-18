<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 */

get_header(); ?>

<div class="main-content content-news-archive">
	<div class="page-left-menu">
		<?php
			getArchiveLeftMenu();
		?>
	</div>
	<div class="page-inside-content">

			<?php if ( have_posts() ) : ?>

				<header class="content-news-header">
					<div class="tag-title">
						<?php if ( is_day() ) : ?>
							<?php echo '<span>' . get_the_date() . '</span>'; ?>
						<?php elseif ( is_month() ) : ?>
							<?php echo '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'hk-wp-mall' ) ) . '</span>'; ?>
						<?php elseif ( is_year() ) : ?>
							<?php  echo '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'hk-wp-mall' ) ) . '</span>'; ?>
						<?php else : ?>
							<?php
								single_tag_title();
							?>
						<?php endif; ?>
					</div>
						<?php getPaginationHTML(); ?>
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

				<?php endwhile; 
					getPaginationHTML();
				?>
			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'hk-wp-mall' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'hk-wp-mall' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>