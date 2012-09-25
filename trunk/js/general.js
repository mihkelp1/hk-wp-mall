var avatar;

jQuery(document).ready(function($){

	$('div.person').each(function() {
		$(this).hover(function() {
			var qr_image = $(this).find('img.qr-image:first');
			avatar = $(this).find('img:first');
			avatar.orig_src = avatar.attr('src');
			avatar.hide();
			avatar.attr('src', qr_image.attr('src')).fadeIn('fast');
		}, 
		function() {
			avatar.hide();
			avatar.attr('src', avatar.orig_src).fadeIn('fast');
		});
	});
	
	/* Search related Javascript */
	
	/* Search link */
		
	var search_esc_closed = false;
	var searchform = $('div.#rollout-searchform');
	
	
	$('.header-menu-container li:nth-child(5)').bind( 'click', function(event) {
		event.preventDefault();
		event.stopPropagation();
	});
	
	$('.header-menu-container li:nth-child(5)').bind('click', function() {
		var pos = $(this).position();
		if ( !search_esc_closed ) {
			$('div.#rollout-searchform').css({
				'right': $(document).width() - pos.left - $(this).outerWidth()
			});
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
		if ( $(this).is(':visible') ) {
			$(this).focus();
		}
	});
});