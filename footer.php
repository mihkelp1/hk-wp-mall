<?php
/**
 * The template for displaying the footer.
 */
?>

<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-left', 'container_class' => 'footer-menu-container-left' ) ); ?>

<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-middle', 'container_class' => 'footer-menu-container-middle' ) ); ?>

<?php wp_nav_menu( array( 'theme_location' => 'footer-menu-right', 'container_class' => 'footer-menu-container-right' ) ); ?>

</body>
</html>