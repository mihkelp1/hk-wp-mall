<?php
/**
 * The default template for displaying content
 */
?>

<div style="height: 455px; border: 1px solid yellow;">
	kursused
</div>

<ul id="slider1">
  <li><div>Slide one content</div></li>
  <li><div>Slide two content</div></li>
  <li><div>Slide one content</div></li>
  <li><div>Slide two content</div></li>
  <li><div>Slide one content</div></li>
  <li><div>Slide two content</div></li>
  
</ul>

<script type="text/javascript">
  jQuery(document).ready(function($){
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
