jQuery(document).ready(function($) {
	if ( hkAdmin.isLandingPage == "true" ) {
		$('#upload_logo_button').click(function(e) {
			e.preventDefault();
			window.old_send_to_editor = window.send_to_editor; //Need to keep old function reference
			
			window.send_to_editor = override_send_to_editor;
			tb_show(hkAdmin.pickLandingImage, 'media-upload.php?referer=hk-landing-page&type=image&TB_iframe=true&post_id=0', false);
			return false;
		});		
		
		$('#remove_landing_thumbnail').click(function(e) {
			e.preventDefault();
			if ( confirm(hkAdmin.areYouSure) ) {
				$('#imgURL').html("");
				$('#landing-image-id').val(0);
				$('#upload_logo_button').removeClass('hide-if-landing-thumbnail');
				$(this).addClass('hide-if-landing-thumbnail');
			}
		});
	}
	
	$('#hk-reminder-send').submit(function() {
		return confirm(hkAdmin.areYouSure);
	});
	
	function override_send_to_editor(html) {
		var html = $(html);
		var image = $(html);
		if ( html.prop('tagName') != 'IMG' ) {
			image = html.find('img:first');
		}
		if ( image.length > 0 ) {
			var image_url = image.attr('src');
			var image_id = image.attr('class').match(/\d+/gi);
			$('#imgURL').html(html);
			$('#landing-image-id').val(image_id);
			$('#upload_logo_button').addClass('hide-if-landing-thumbnail');
			$('#remove_landing_thumbnail').removeClass('hide-if-landing-thumbnail');
		}
		tb_remove();
		//Revert back to old function
		window.send_to_editor = window.old_send_to_editor;
	}
});
