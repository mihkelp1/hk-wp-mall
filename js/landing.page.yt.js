jQuery(document).ready(function($) {
	// 2. This code loads the IFrame Player API code asynchronously.
	var tag = document.createElement('script');
	tag.src = "//www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
});

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;
function onYouTubeIframeAPIReady() {
	player = new YT.Player('player', {
	  height: '390',
	  width: '640',
	  playerVars: {
		showinfo: 0,
		modestbranding: 1,
		rel: 0,
		theme: 'light',
		autohide: 1
	  },
	  events: {
		'onReady': onPlayerReady
	  }
	});
}

function stopVideo() {
	player.stopVideo();
	player.clearVideo();
}

// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
	player.loadVideoById( landingPageVideo.videoId );
	jQuery("#TB_closeWindowButton").bind('click', function() {
		stopVideo();
	});
	
	jQuery('#TB_overlay').bind('click', function() {
		stopVideo();
	});
}