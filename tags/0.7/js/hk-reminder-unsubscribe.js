jQuery(document).ready(function($){
	var dialog = $('<div>'+hk.reminderUnsubscribed+'<input type="button" value="'+hk.reminderClose+'" id="hk-unsubscribe-close" /></div>').dialog({
			width: 380,
			minWidth: 380,
			modal: true,
			resizable: false,
			draggable: false,
			open: function() {
				$('#hk-unsubscribe-close').blur();
				$('#hk-unsubscribe-close').click(function() {
					dialog.dialog('close');
				});
			}
		});
		
});