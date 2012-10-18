<?php
/**
 * The template for displaying search forms in the template
 */
?>
	<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" tabindex="2" value="<?php the_search_query(); ?>" class="search-field" name="s" autofocus="autofocus" placeholder="<?php esc_attr_e( 'Search', 'hk-wp-mall' ); ?>" />
	</form>