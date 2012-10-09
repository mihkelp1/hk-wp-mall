jQuery(document).ready(function($){

	$('div.aim').find('a:nth-child(-n+4)').each(function() {
		//IF IE 9 > and other browsers
		if (jQuery.support.leadingWhitespace) {
			var old_bg = $(this).css('background-image');
			$(this).css({'background' : 'url("wp-content/themes/hk-wp-mall-news-right/images/home-nav-shadow.png") right -10px no-repeat,'+ old_bg + 'no-repeat' });
		}
		
	});
	
	$('div.aim').find('a').each(function() {
		
		var span = $(this).find('span:first');
		$(this).hover(function(e) {
			span.hoverFlow(e.type, {
				marginLeft: '100px'
			}, 400);
		},
		function(e) {
			span.hoverFlow(e.type, {
				marginLeft: '0px'
			}, 375);
		});
	});
});