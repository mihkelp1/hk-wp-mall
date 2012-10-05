<?php
/**
 * The template for displaying the footer.
 */
?>

	<!-- Close page-content -->
	</div>

	<div id="footer-wrapper">
		<footer class="page-footer">
			<div class="footer-menus-wrapper">
				<nav class="footer-nav-menu">
					<h2 class="menu-title"><?php echo wp_nav_menu_title( 'footer-menu-left' ); ?></h2>
					<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-left', 'container_class' => 'footer-menu-container-left', 'fallback_cb' => false, 'walker' => new isDraftMenuWalker() ) ); ?>
				</nav>
				<nav class="footer-nav-menu footer-nav-menu-padding footer-nav-menu-middle">
					<h2 class="menu-title"><?php echo wp_nav_menu_title( 'footer-menu-middle' ); ?></h2>
					<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-middle', 'container_class' => 'footer-menu-container-middle', 'fallback_cb' => false, 'walker' => new isDraftMenuWalker() ) ); ?>
				</nav>
				<nav class="footer-nav-menu footer-nav-menu-padding footer-right-menu no-border">
					<h2 class="menu-title"><?php echo wp_nav_menu_title( 'footer-menu-right' ); ?></h2>
					<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-right', 'container_class' => 'footer-menu-container-right', 'fallback_cb' => false, 'walker' => new isDraftMenuWalker() ) ); ?>
				</nav>
			</div>
		<div id="footer-banner">
			<?php 
				if ( function_exists( 'wp_bannerize' ) ) {
					wp_bannerize( 'random=1&limit=1' ); 
				}
			?>
		</div>
		</footer>
	</div>
	<div id="sidebar-wrapper">
		<?php
			/* A sidebar in the footer? Yep. You can can customize
			 * your footer with three columns of widgets.
			 */
				get_sidebar( 'footer' );
			?>
			<!-- Facebook plugin -->
			<div id="likebox-frame">
			<script src="http://connect.facebook.net/et_EE/all.js#xfbml=1" type="text/javascript"></script>
			<script type="text/javascript">
			//<![CDATA[
				document.write('<fb:like-box href="http://www.facebook.com/kolledz" width="952" height="180" show_faces="true" stream="false" header="false"></fb:like-box>');
			//]]>
			</script>
			<!-- Facebook plugin end -->	
	</div>
	
<!-- Close "main-conteiner" -->
</div>

<?php 
	wp_footer();
?>

</body>
</html>