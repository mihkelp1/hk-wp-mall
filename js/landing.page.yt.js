jQuery(document).ready(function($) {
	// 2. This code loads the IFrame Player API code asynchronously.
	var tag = document.createElement('script');
	tag.src = "//www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	
	var player_was_paused = false;
	
	$('#landing-video-button').bind('click', function(event) {
		event.preventDefault();
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
					init_YT_Player();
				}
			},
			beforeClose: function() {
				player.pauseVideo();
				player_was_paused = true;
			}
		});
	});
	
	$('#reminderForm').submit(function() {
		var data = $(this).serialize();
		$.post(landingPageMeta.ajaxUrl, data, function(response) {
			alert(response.status);
		},
		'json'
		);
		return false;
	});
	
	$('#landing-reminder-button').bind('click', function(event) {
		
		event.preventDefault();
		$('#reminder-wrapper').dialog({
			width: 380,
			minWidth: 380,
			minHeight: 250,
			modal: true,
			resizable: false,
			draggable: false
		});
	});
});


var player;
function init_YT_Player() {
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

// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
	player.loadVideoById( landingPageMeta.videoId );
}