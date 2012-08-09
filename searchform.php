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
		<input type="text" class="search-field" name="s" id="s" autofocus="autofocus" placeholder="<?php esc_attr_e( 'Search', 'hk-wp-mall' ); ?>" />
	</form>

<script type="text/javascript">	
	jQuery(document).ready(function($){
		/* Search link */
		
		var search_esc_closed = false;
		
		$('.header-menu-container li:nth-child(5)').bind( 'click', function(event) {
			event.preventDefault();
			event.stopPropagation();
		});
		
		$('.header-menu-container li:nth-child(5)').bind('mouseenter', function() {
			if ( !search_esc_closed ) {
				$('div.#rollout-searchform').animate({
					'width' : 'show'
				}, 'fast', function() {
					$('div.#rollout-searchform').find('input:first').focus();
				});
			}
		});
		
		$('.header-menu-container li:nth-child(5)').bind('mouseleave', function() {
			search_esc_closed = false;
		});
		
		$(document).live('click', function(e) {
			var searchform = $('div.#rollout-searchform');
			if ( e.target != searchform ) {
				if ( searchform.is(':visible') ) {
					$('div.#rollout-searchform').animate({
						'width' : 'hide'
					}, 'fast');
				}
			}
		});
		
		$('div.#rollout-searchform').bind('click', function(e) {
			e.stopPropagation();
		});
		
		$('div.#rollout-searchform').bind('keyup', function(e) {
			alert('opera'+e.keyCode);
			if ( e.keyCode == 27 ) {
				if ( $(this).is(':visible') ) {
					$(this).animate({
						'width' : 'hide'
					}, 'fast');
					search_esc_closed = true;
					setTimeout( function() {
						//Clear flag
						search_esc_closed = false;
					}, 250 );
				}
			}
		});
	});
</script>
