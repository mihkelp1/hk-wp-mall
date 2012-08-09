<?php
/**
 * The template for displaying search forms in Twenty Eleven
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'hk-wp-mall' ); ?></label>
		<input type="text" class="search-field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'hk-wp-mall' ); ?>" />
	</form>
