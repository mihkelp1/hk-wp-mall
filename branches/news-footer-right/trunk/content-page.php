<?php
/**
 * The default template for displaying content
 */
?>

<div class="main-content content-page">

	<div class="page-left-menu">
		<?php
			$child_of = $post->post_parent ? $post->post_parent : get_the_ID();
			if ( count( $post->ancestors ) > 1 ) {
				$child_of = $post->ancestors[1];
			}
			$depth = ( get_children( get_the_ID() ) && $post->post_parent ) || count($post->ancestors) > 1 ? 2 : 1;
			$args = array(
				'depth'        => $depth,
				'date_format'  => get_option('date_format'),
				'child_of'     => $child_of,
				'title_li'     => '',
				'echo'         => 0,
				'sort_column'  => 'menu_order, post_title',
				'post_type'    => 'page',
				'post_status'  => 'publish' 
			);
			
			echo '<ul class="parent-pages">';
			$page = get_page( $child_of );
			if ( $page && !empty( $page->post_content ) ) {
				?>
				<li class="<?php if ( get_the_ID() == $child_of ) { echo 'current_page_item'; } ?>"><a href="<?php echo get_permalink($child_of);?>" title="<?php echo get_the_title($child_of);?>"><?php echo get_the_title($child_of);?></a></li>
				<?php
			}
			$menu_str = wp_list_pages( $args );
			if ( empty( $menu_str ) ) {
				echo '<div class="last-resort-menu-spacer"></div>';
				wp_nav_menu( array( 'theme_location' => 'last-resort-menu', 'container_class' => 'last-resort-menu-container' ) );
			} else {
				echo $menu_str;
			}
			echo '</ul>';
		?>

	</div>
	<div class="page-inside-content">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<div class="entry-meta">
					<?php edit_post_link( __( 'Edit', 'hk-wp-mall' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .entry-meta -->
			</header><!-- .entry-header -->
		
			<div class="entry-content">
				<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail('large');
					} 
				?>
				<?php the_content(); ?>
			</div><!-- .entry-content -->
		</article><!-- #post-<?php the_ID(); ?> -->
	</div>
	
</div>

