jQuery(document).ready(function($) {
	$('#upload_logo_button').click(function(e) {
		e.preventDefault();
		tb_show(hkAdmin.pickLandingImage, 'media-upload.php?referer=hk-landing-page&type=image&TB_iframe=true&post_id=0', false);
		return false;
	});			
	window.send_to_editor = function(html) {
		var html = $(html);
		var image = $(html);
		if ( html.prop('tagName') != 'IMG' ) {
			image = html.find('img:first');
		}
		var image_url = image.attr('src');
		var image_id = image.attr('class').match(/\d+/gi);
		$('#imgURL').html(html);
		$('#landing-image-id').val(image_id);
		if ( image.length > 0 ) {
			$('#upload_logo_button').addClass('hide-if-landing-thumbnail');
			$('#remove_landing_thumbnail').removeClass('hide-if-landing-thumbnail');
		}
		tb_remove();
	}
	
	$('#remove_landing_thumbnail').click(function(e) {
		e.preventDefault();
		if ( confirm(hkAdmin.areYouSure) ) {
			$('#imgURL').html("");
			$('#landing-image-id').val(0);
			$('#upload_logo_button').removeClass('hide-if-landing-thumbnail');
			$(this).addClass('hide-if-landing-thumbnail');
		}
	});
});
