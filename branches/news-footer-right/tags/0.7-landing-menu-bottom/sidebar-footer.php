<?php
/**
 * The Footer widget areas.
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if ( !is_active_sidebar( 'footer-sidebar' ) ) {
		return;
	}
	// If we get this far, we have widgets. Let do this.
?>

<?php if ( is_active_sidebar( 'footer-sidebar' ) ) : ?>
<div id="footer-sidebar" class="widget-area" role="complementary">
	<?php dynamic_sidebar( 'footer-sidebar' ); ?>
	<a href="http://www.facebook.com/kolledz"><img src="<?php echo getFileURL('/images/f_logo.png'); ?>"/></a>
</div><!-- #first .widget-area -->
<?php endif; ?>