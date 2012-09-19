<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
			if ( is_search() ) {
		?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="search-article-header"><h1 class="entry-title"><?php the_title(); ?></h1></a>
		<?php
			} else {
		?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php 
			}
		?>
	<div class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'hk-wp-mall' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			if ( has_post_thumbnail() ) {
				if ( is_search() ) {
					the_post_thumbnail( 'medium' );
				} else {
					the_post_thumbnail();
				}
			} 
		?>
		<?php
			if ( is_search() ) {
			?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php
				the_excerpt();
			?>
			</a>
			<?php
			} else {
				the_content(); 
			}
			
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', __( ', ', 'hk-wp-mall' ) );
			if ( '' != $tag_list ) {
				$utility_text = '<div class="tags-list">'.__('Tags: %1$s', 'hk-wp-mall' ).'</div>';
			}

			printf(
				$utility_text,
				$tag_list
			);
		?>
		<?php 
			if ( get_post_type() == 'post' ) { 
				_posted_on(); 
			}
			?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
