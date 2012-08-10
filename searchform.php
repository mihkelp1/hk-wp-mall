<?php
/**
 * The template for displaying search forms in Twenty Eleven
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>
	<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" tabindex="2" class="search-field" name="s" autofocus="autofocus" placeholder="<?php esc_attr_e( 'Search', 'hk-wp-mall' ); ?>" />
	</form>

<script type="text/javascript">	
	jQuery(document).ready(function($){
		/* Search link */
		
		var search_esc_closed = false;
		var searchform = $('div.#rollout-searchform');
		
		
		$('.header-menu-container li:nth-child(5)').bind( 'click', function(event) {
			event.preventDefault();
			event.stopPropagation();
		});
		
		$('.header-menu-container li:nth-child(5)').bind('click', function() {
			if ( !search_esc_closed ) {
				$('div.#rollout-searchform').animate({
					'width' : 'show'
				}, 'fast', function() {
					searchform.find('input:first').focus();
				});
			}
		});
		
		$(document).live('click', function(e) {
			if ( searchform.is(':visible') ) {
				$('div.#rollout-searchform').animate({
					'width' : 'hide'
				}, 'fast');
			}
		});
		
		searchform.bind('click', function(e) {
			e.stopPropagation();
		});
		
		searchform.bind('keyup', function(e) {
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
		
		$('input[class="search-field"]').each(function() {
			$(this).placeholder();
			if ( $(this).is(':visible') ) {
				$(this).focus();
			}
		});
	});
</script>
