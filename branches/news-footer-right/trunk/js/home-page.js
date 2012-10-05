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


$('#slider1').bxSlider({
	displaySlideQty: 4,
	moveSlideQty: 4,
	speed: 1000,
	onAfterSlide: function( currentSlideNumber, totalSlideQty, currentSlideHtmlObject ) {
		var all_lis = currentSlideHtmlObject.parent().find('li').each(function() {
			var li = $(this);
			if ( li.hasClass( 'no-news-li-background' ) ) {
				li.removeClass( 'no-news-li-background' );
			}
		});
		var last_li = currentSlideHtmlObject.next().next().next();
		last_li.addClass( 'no-news-li-background' );
	},
	onBeforeSlide: function( currentSlideNumber, totalSlideQty, currentSlideHtmlObject ) {
		var all_lis = currentSlideHtmlObject.parent().find('li').each(function() {
			var li = $(this);
			if ( !li.hasClass( 'no-news-li-background' ) ) {
				li.addClass( 'no-news-li-background' );
			}
		});
	}
});
});