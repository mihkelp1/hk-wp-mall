jQuery(document).ready(function($) {
	// 2. This code loads the IFrame Player API code asynchronously.
	var tag = document.createElement('script');
	tag.src = "//www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	
	var player_was_paused = false;
	
	$('#playLandingVideo').bind('click', function() {
		$('#playerWrapper').dialog({
			width: 680,
			minWidth: 680,
			minHeight: 450,
			modal: true,
			resizable: false,
			draggable: false,
			open: function() {
				if ( player_was_paused ) {
					setTimeout( function() {
						player.playVideo();
					}, 150 );
				} else {
					player.playVideo();
				}
			},
			beforeClose: function() {
				player.pauseVideo();
				player_was_paused = true;
			}
		});
	});
});

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;
function onYouTubeIframeAPIReady() {
	player = new YT.Player('player', {
	  height: '390',
	  width: '640',
	  videoId: landingPageVideo.videoId,
	  playerVars: {
		showinfo: 0,
		modestbranding: 1,
		rel: 0,
		theme: 'light',
		autohide: 1
	  }
	});
}