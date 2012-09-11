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
});