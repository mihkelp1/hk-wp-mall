var player;
var player_was_paused = false;
var is_ie7 = false;
var posting = false;

jQuery(document).ready(function($) {
	// 2. This code loads the IFrame Player API code asynchronously.
	var tag = document.createElement('script');
	tag.src = "//www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	
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
				if ( player ) {
					player.pauseVideo();
					player_was_paused = true;
				}
			}
		});
	});
	
	$('#reminderForm').submit(function() {
		if ( !posting ) {
			posting = true;
			var messageDiv = $(this).find('#hk-reminder-status');
			var data = $(this).serialize();
			var submit_btn = $(this).find('#hk-submit-btn');
			
			submit_btn.hide();
			messageDiv.fadeIn('fast');
			
			setTimeout( function() {
			$.post(landingPageMeta.ajaxUrl, data, function(response) {
				messageDiv.find('img:first').hide();
				messageDiv.find('span:first').html("Status response " + response.status );
				//Reset status related stuff
				setTimeout( function() {
					$('#hk-reminder-status').hide();
					$('#hk-reminder-status').find('img:first').show();
					$('#hk-reminder-status').find('span:first').html("");
					$('#hk-submit-btn').fadeIn('fast');
					posting = false;
				}, 4000);
			},
			'json'
			);
			}, 2500 );
		}
		return false;
	});
	
	$('#landing-reminder-button').bind('click', function(event) {
		
		event.preventDefault();
		$('#reminder-wrapper').dialog({
			width: 380,
			minWidth: 380,
			modal: true,
			resizable: false,
			draggable: false, 
			beforeClose: function() {
				$('#reminderForm')[0].reset();
			}
		});
	});
});

function init_YT_Player() {
	// IF IE7 or less, use Youtube JavaScript API
	if ( is_ie7 ) {
		var params = { allowScriptAccess: "always" };
		var atts = { id: "yt_player" };
		swfobject.embedSWF("http://www.youtube.com/v/"+landingPageMeta.videoId+"?enablejsapi=1&playerapiid=ytplayer&version=3&autohide=1&showinfo=0&modestbranding=1&rel=0&theme=light",
                       "player", "640", "390", "8", null, null, params, atts);
    } else {
    	//Otherwise use more modern iFrame API
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
}

/* This method is called by Youtube JavaScript API */
function onYouTubePlayerReady(playerId) {
	player = document.getElementById("yt_player");
	player.playVideo();
}

/* This method is called by Youtube iFrame API */
function onPlayerReady(event) {
	player.loadVideoById( landingPageMeta.videoId );
}