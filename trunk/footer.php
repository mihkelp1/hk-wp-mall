<?php
/**
 * The template for displaying the footer.
 */
?>

	<!-- Close page-content -->
	</div>

	<div id="footer-wrapper">
		<footer>
			<div class="footer-menus-wrapper">
				<nav class="footer-nav-menu">
					<h2 class="menu-title"><?php echo wp_nav_menu_title( 'footer-menu-left' ); ?></h2>
					<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-left', 'container_class' => 'footer-menu-container-left' ) ); ?>
				</nav>
				<nav class="footer-nav-menu">
					<h2 class="menu-title"><?php echo wp_nav_menu_title( 'footer-menu-middle' ); ?></h2>
					<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-middle', 'container_class' => 'footer-menu-container-middle' ) ); ?>
				</nav>
				<nav class="footer-nav-menu footer-right-menu no-border">
					<h2 class="menu-title"><?php echo wp_nav_menu_title( 'footer-menu-right' ); ?></h2>
					<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-right', 'container_class' => 'footer-menu-container-right' ) ); ?>
				</nav>
			</div>
		<?php
			/* A sidebar in the footer? Yep. You can can customize
			 * your footer with three columns of widgets.
			 */
			if ( ! is_404() )
				get_sidebar( 'footer' );
		?>
		</footer>
	</div>
	
<!-- Close "main-conteiner" -->
</div>

<?php 
	wp_footer();
?>

</body>
</html>