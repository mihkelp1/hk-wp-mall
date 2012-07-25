<?php
/**
 * The default template for displaying content
 */
?>

<ul id="slider1">
  <li >Slide one content</li>
  <li>Slide two content</li>
  <li>Slide three content</li>
  <li>And so on...</li>
  <li>And so on...1</li>
  <li>And so on...2</li>
  <li>And so on...3</li>
  
</ul>

<script type="text/javascript">
  jQuery(document).ready(function($){
    $('#slider1').bxSlider({
    	displaySlideQty: 4,
	    moveSlideQty: 1
    });
  });
</script>
