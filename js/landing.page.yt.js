var player;
var player_was_paused = false;
var is_ie7 = false;
var posting = false;
var post_request;
var generalTimeout;

function reminderFormReset($) {
	//Reset status related stuff
	clearTimeout(generalTimeout);
	setTimeout( function() {
		$('#hk-reminder-status').hide();
		$('#hk-reminder-status').find('img:first').show();
		$('#hk-reminder-status').find('span:first').html("");
		$('#hk-submit-btn').fadeIn('fast');
		posting = false;
	}, 4000);
}

jQuery(document).ready(function($) {
	// 2. This code loads the IFrame Player API code asynchronously.
	var tag = document.createElement('script');
	tag.src = "//www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	
	$('#landing-video-button').bind('click', function(event) {
		event.preventDefault();
		$('#playerWrapper').dialog({
			width: 743,
			minWidth: 743,
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
		var messageDiv = $(this).find('#hk-reminder-status');
		if ( !posting ) {
			//Set timeout for checking if still posting, probably something went wrong
			//Timeout set to 15 seconds
			generalTimeout = setTimeout( function() {
				if ( posting ) {
					post_request.abort();
					messageDiv.find('img:first').hide();
					messageDiv.find('span:first').html(landingPageMeta.reminderGeneral);
					//Init reminder form reset
					reminderFormReset($);
				}
			}, 15000 );
		
			posting = true;
			var data = $(this).serialize();
			var submit_btn = $(this).find('#hk-submit-btn');
			
			submit_btn.hide();
			messageDiv.fadeIn('fast');
			
			post_request = $.post(landingPageMeta.ajaxUrl, data, function(response) {
				messageDiv.find('img:first').hide();
				var msg;
				switch(response.status) {
					case 1:
						msg = landingPageMeta.reminderSuccess;
						break;
					case 2:
						msg = landingPageMeta.reminderExist;
						break;
					case 3:
						msg = landingPageMeta.reminderEmail;
						break;
					case 4:
						msg = landingPageMeta.reminderHack;
						break;
					default:
						msg = landingPageMeta.reminderGeneral;
				}
				
				messageDiv.find('span:first').html(msg);
				//Init reminder form reset
				reminderFormReset($);
			},
			'json'
			);
		}
		return false;
	});
	
	$('#landing-reminder-button').bind('click', function(event) {
		
		event.preventDefault();
		$('#reminder-wrapper').dialog({
			width: 380,
			minWidth: 380,
			height: 230,
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
                       "player", "693", "390", "8", null, null, params, atts);
    } else {
    	//Otherwise use more modern iFrame API
    	player = new YT.Player('player', {
		  height: '390',
		  width: '693',
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