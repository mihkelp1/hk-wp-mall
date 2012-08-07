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
  
  	setTimeout(function() {
		$('div.aim').find('a:nth-child(-n+4)').each(function() {
			var old_bg = $(this).css('background-image');
			$(this).css({'background' : 'url("wp-content/themes/hk-wp-mall/images/home-nav-shadow.png") right 35px no-repeat,'+ old_bg + 'no-repeat' });
		});
	}, 250 );
  
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
