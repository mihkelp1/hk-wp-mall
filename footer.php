<?php
/**
 * The template for displaying the footer.
 */
?>

	<!-- Close page-content -->
	</div>

	<div id="footer-wrapper">
		<footer>
			<nav>
				<h2>Info tudengile</h2>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-left', 'container_class' => 'footer-menu-container-left' ) ); ?>
			</nav>
			<nav>
				<h2>Info sisseastujatele</h2>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-middle', 'container_class' => 'footer-menu-container-middle' ) ); ?>
			</nav>
			<nav>
				<h2>Arendusprojektid</h2>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-right', 'container_class' => 'footer-menu-container-right' ) ); ?>
			</nav>
		</footer>
	</div>
	
<!-- Close "main-conteiner" -->
</div>

<?php 
	wp_footer();
?>

</body>
</html>