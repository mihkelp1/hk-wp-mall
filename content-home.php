<?php
/**
 * The default template for displaying content
 */
?>

<div class="main-content content-home">
	<?php echo do_shortcode('[a_image_menu]') ?>
</div>

<?php getNewsSlider(); ?>

<script type="text/javascript">
  jQuery(document).ready(function($){
  
	$('div.aim').find('a:nth-child(-n+4)').each(function() {
		var old_bg = $(this).css('background-image');
		$(this).css({'background' : 'url("wp-content/themes/hk-wp-mall/images/home-nav-shadow.png") right -10px no-repeat,'+ old_bg + 'no-repeat' });
		
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
	
	/* Search link */
	
	$('.header-menu-container li:nth-child(5)').bind( 'click', function(event) {
		event.preventDefault();
		event.stopPropagation();
	});
	
	$('.header-menu-container li:nth-child(5)').hover(function() {
		$('div.#rollout-searchform').animate({
			'width' : 'show'
		}, 'fast', function() {
			$('div.#rollout-searchform').find('input:first').focus();
		});
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
	$('div.#rollout-searchform').hide();
  
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
</script>
