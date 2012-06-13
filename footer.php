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
					<h2 class="menu-title">Info tudengile</h2>
					<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-left', 'container_class' => 'footer-menu-container-left' ) ); ?>
				</nav>
				<nav class="footer-nav-menu">
					<h2 class="menu-title">Info sisseastujatele</h2>
					<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-middle', 'container_class' => 'footer-menu-container-middle' ) ); ?>
				</nav>
				<nav class="footer-nav-menu no-border">
					<h2 class="menu-title">Arendusprojektid</h2>
					<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-right', 'container_class' => 'footer-menu-container-right' ) ); ?>
				</nav>
			</footer>
		</footer>
	</div>
	
<!-- Close "main-conteiner" -->
</div>

<?php 
	wp_footer();
?>

</body>
</html>