<?php
/**
 * The template for displaying content in the single.php template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
			if ( is_search() ) {
		?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="search-article-header"><h1 class="entry-title"><?php the_parentTitle( $post ); the_title(); ?></h1></a>
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
				$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				echo '<a href="'.$url.'" rel="lightbox['.$post->ID.']" title="'.$post->post_title.'">';
				the_post_thumbnail('medium');
				echo '</a>';
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

<?php if ( is_single() ) {
	echo '<div class="entry-content single-latest-news">';
	the_widget( 'WP_Widget_Recent_Posts', array( 'title' =>  __('Recent news', 'hk-wp-mall'), 'number' => 10 ), array('before_title' => '<h2 class="entry-title">', 'after_title' => '</h2>') );
	echo '</div>';
}