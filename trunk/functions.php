<?php

/**
 * Our theme actions hooks, filters and helper methods
 *
 * By Raido Kuli 2012
 */

//TODO clean up slider files, styles, js and so on
//TODO remove footer banner styles

require_once( 'class-menu-walker.php' );

/* Disable some dashboard widgets */

function disable_dashboard_widgets() {
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
} 

add_action('wp_dashboard_setup', 'disable_dashboard_widgets' );

/* EOF Hook into the 'wp_dashboard_setup' action to register our function */


/*
 * Disable some permissions and pages we dont need
 */

function give_editor_role_permissions() {
        if(current_user_can('edit_others_posts')) {
                global $wp_roles;
                $wp_roles->add_cap('editor','edit_theme_options' );
                $wp_roles->add_cap('editor', 'manage_options' );
                $wp_roles->remove_cap('editor', 'edit_themes' );
                $wp_roles->remove_cap('editor', 'manage_links' );
        }
}

add_action('admin_init', 'give_editor_role_permissions', 10, 0);

add_action( 'admin_menu', 'removeMenuPages' );

function removeMenuPages() {
	remove_menu_page('link-manager.php');
	if ( !is_super_admin() ) {
		remove_menu_page('tools.php');
	}
}


/* EOF */

/**
 * Register 4 menus for using on the page
 *
 */
 
function _initTheme() {
	//Load textdomain file for site language
	load_theme_textdomain( 'hk-wp-mall', get_template_directory() . '/languages' );
	
	//Register nav menus
 	register_nav_menus(
		array( 'header-menu' => __( 'Header menu', 'hk-wp-mall' ),
			'nav-menu' => __( 'Navigation menu', 'hk-wp-mall' ),
			'footer-menu-left' => __('Footer left', 'hk-wp-mall' ),
			'footer-menu-middle' => __('Footer middle', 'hk-wp-mall' ),
			'footer-menu-right' => __('Footer right', 'hk-wp-mall' ),
			'last-resort-menu' => __('General menu', 'hk-wp-mall' ) )
		 );
		
	register_sidebar(array(
	  'name' => __( 'Footer sidebar', 'hk-wp-mall' ),
	  'id' => 'footer-sidebar',
	  'before_widget' => '<div id="%1$s" class="widget %2$s">',
	  'after_widget'  => '</div>',
	  'description' => __( 'Widgets in this area will be shown in the footer.', 'hk-wp-mall' )
	));
	
	add_theme_support( 'post-thumbnails' );
	
	global $wpdb;
	$table_name = $wpdb->prefix . "hk_reminders";
	$table_history = $wpdb->prefix. 'hk_reminders_history';
	require_once( 'class-reminders.php' );
	if ( $wpdb->get_var( 'show tables like "'.$table_name.'"') != $table_name ) {
		$sql = "CREATE TABLE " . $table_name . " (
		id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		email varchar(250) NOT NULL,
		flag varchar(250) DEFAULT '',
		date TIMESTAMP,
		KEY (email) );";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');		
		dbDelta($sql);
	}
	
	if ( $wpdb->get_var( 'show tables like "'.$table_history.'"') != $table_history ) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');		
		$sql = "CREATE TABLE " . $table_history . " (
		id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		count INT(11),
		date TIMESTAMP);";	
		dbDelta($sql);
	}
}

add_action( 'after_setup_theme', '_initTheme' );

add_filter( 'people_list_shortcode', 'contactsListFilter' );

function contactsListFilter( $html ) {
	return str_replace( '%3A+372', '%3A%2B372', $html );
}

//Reminder functions
add_action( 'admin_menu', 'createReminderMenu' );

function reminder_shortcode( $atts ) {
	//If not enabled, bail out
	if ( !HK_Reminders::isEnabled() ) {
		return;
	}
	
	extract( shortcode_atts( array(
		'flag' => '-',
		'text' => ''
	), $atts ) );
	
	echo '<div id="reminder-wrapper" style="display:none;">';
	echo '<p>'.HK_Reminders::getSetting( 'modal-welcome-text' ).'</p>';
	echo '<form method="post" action="'.admin_url( 'admin-ajax.php' ).'" id="reminderForm">';
	echo '<input type="hidden" name="action" value="subscribe_reminder" />';
	echo '<input type="hidden" name="security" value="'.wp_create_nonce('hk-reminder-nonce').'" />';
	echo '<input type="hidden" name="security_2_step" value="'.wp_create_nonce( $flag ).'" />';
	echo '<input type="hidden" name="reminder_flag" value="'.$flag.'" />';
	echo '<input type="email" name="reminder_email" id="reminder_email" class="visible" placeholder="'.__( 'Your email', 'hk-wp-mall' ).'" style="width: 340px"/>';
	echo '<div id="hk-reminder-status" style="display:none; float:right;"><p><span></span><img src="'.get_template_directory_uri().'/images/ajax-loader.gif" alt="AJAX Loader" /></p></div>';
	echo '';
	echo '<input type="submit" value="'.__( 'Subscribe', 'hk-wp-mall' ).'" style="float: right;" class="visible" id="hk-submit-btn" />';
	echo '</form></div>';
	
	?>
	<div class="landing-reminder-button" id="landing-reminder-button">
		<div><?php _e( 'Intrested in this curriculum ?', 'hk-wp-mall' ); ?></div>
		<a href="#" id="subscribeReminder"><?php _e( 'SUBSCRIBE', 'hk-wp-mall' ); ?></a>
	</div>
	<?php
}

add_shortcode( 'hk_reminder', 'reminder_shortcode' );

/* Enable reminders subscription ajax calls for both logged not logged in users */
add_action('wp_ajax_nopriv_subscribe_reminder', 'subscribeReminderFunc');
add_action('wp_ajax_subscribe_reminder', 'subscribeReminderFunc');

function subscribeReminderFunc() {
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	
	$status = 0;
	if ( check_ajax_referer( 'hk-reminder-nonce', 'security', false ) ) {
		$remindersHandler = new HK_Reminders();
		$email = sanitize_text_field( $_POST['reminder_email'] );
		$flag = sanitize_text_field( $_POST['reminder_flag'] );
		$flag_nonce = sanitize_text_field( $_POST['security_2_step'] );
		//If flag has not been altered, proceed
		if ( wp_verify_nonce( $flag_nonce, $flag ) ) {
			//If is valid email proceed
			if ( is_email( $email ) ) {
				$reminder_added = $remindersHandler->addReminder( $email, $flag );
				if ( $reminder_added ) {				
					$msg_content = str_replace( '{unsubscribe_link}', '<a href="'.add_query_arg( array('unsubscribe' => md5( $email ) ), home_url() ).'">'.__( 'click here', 'hk-wp-mall' ).'</a>', HK_Reminders::getSetting( 'confirmation-email' ) );
					$wp_send_success = HK_Reminders::send_mail( $email, HK_Reminders::getSetting( 'confirmation-email-subject' ), $msg_content );
					if ( $wp_send_success) {
						//Subscribe success
						$status = 1;
					}
				} else {
					//Already subscribed
					$status = 2;
				}
			} else {
				//Not an valid email
				$status = 3;
			}
		} else {
			//Trying to alter security checks
			$status = 4;
		}
	} else {
		//Trying to alter security checks
		$status = 4;
	}
	echo json_encode( array( 'status' => $status ) );
	die;
}


function createReminderMenu() {
	//TODO fix permission, create custom permission instead of using read permission ??
	add_menu_page( __( 'Reminders', 'hk-wp-mall' ), __( 'Reminders', 'hk-wp-mall' ), 'read', 'hk-reminders', 'createRemindersPage');
	add_submenu_page( 'hk-reminders', __( 'Subscribed', 'hk-wp-mall' ), __( 'Subscribed', 'hk-wp-mall' ), 'read', 'hk-reminders', 'createRemindersPage');
	add_submenu_page( 'hk-reminders', '', __( 'Send reminder', 'hk-wp-mall' ), 'manage_options', 'hk-reminders-send', 'sendEmailNotice');
	add_submenu_page( 'hk-reminders', '', __( 'History', 'hk-wp-mall' ), 'manage_options', 'hk-reminders-history', 'sentReminderHistory');
	add_options_page(__ ('Reminders settings', 'hp-wp-mall'), __( 'Reminders', 'hk-wp-mall' ),'manage_options','hk-reminders-settings', 'createRemindersSettingsPage');
	add_submenu_page( 'hk-reminders', '', __( 'Settings', 'hk-wp-mall' ), 'manage_options', 'options-general.php?page=hk-reminders-settings');
}

add_action( 'admin_init', 'registerReminderSettings' );

function registerReminderSettings() {
	register_setting( 'hk-reminders', 'hk-reminders', 'sanitizeSettings' );
	
	add_settings_section( 'hk-reminders-enable', '', 'enableDisableHeader', 'hk-reminders' );
	add_settings_field( 'hk-reminders-config', __( "Enable reminders", 'hk-wp-mall' ), 'printEnableDisableField', 'hk-reminders', 'hk-reminders-enable', array( 'label_for' => 'hk-reminders-enabled' ) );
	add_settings_section( 'hk-reminders-modal', '', 'accountSettingsHeader', 'hk-reminders' );
	add_settings_field( 'modal-welcome-text', __( "Modal welcome text", 'hk-wp-mall' ), 'printModalWelcomeField', 'hk-reminders', 'hk-reminders-modal', array( 'label_for' => 'modal-welcome-text' ) );
	add_settings_section( 'hk-reminders-confirm-email', '', 'confirmTitle', 'hk-reminders' );
	add_settings_field( 'confirmation-email-subject', __( "Subject", 'hk-wp-mall' ), 'confirmEmailSubject', 'hk-reminders', 'hk-reminders-confirm-email', array( 'label_for' => 'confirmation-email-subject' ) );
	add_settings_field( 'confirmation-email', __( "Body", 'hk-wp-mall' ), 'printConfirmationEmail', 'hk-reminders', 'hk-reminders-confirm-email', array( 'label_for' => 'confirmation-email' ) );

	add_settings_section( 'hk-reminders-confirm-unsubscribe', '', 'confirmUnTitle', 'hk-reminders' );
	add_settings_field( 'confirmation-email-unsubject', __( "Subject", 'hk-wp-mall' ), 'confirmEmailUnSubject', 'hk-reminders', 'hk-reminders-confirm-unsubscribe', array( 'label_for' => 'confirmation-email-unsubject' ) );
	add_settings_field( 'confirmation-email-unbody', __( "Body", 'hk-wp-mall' ), 'printConfirmationUnEmail', 'hk-reminders', 'hk-reminders-confirm-unsubscribe', array( 'label_for' => 'confirmation-unemail' ) );
	
	add_settings_section( 'hk-reminders-confirm-general', '', 'confirmGeneral', 'hk-reminders' );	
	add_settings_field( 'confirmation-email-footer', __( "Footer", 'hk-wp-mall' ), 'printConfirmationEmailFooter', 'hk-reminders', 'hk-reminders-confirm-general', array( 'label_for' => 'confirmation-email-footer' ) );

}

/**
 * Print account settings section header
 */
 
function enableDisableHeader() {
	echo '<h3>'.__( 'Enable reminders', 'hk-wp-mall' ).'</h3>';
}

function printEnableDisableField() {
	$value = HK_Reminders::getSetting('enabled');
	echo '<input type="hidden" name="hk-reminders[enabled]" value="0" />';
	echo '<input type="checkbox" name="hk-reminders[enabled]" id="hk-reminders-enabled" style="width: 572px" value="1" '.( $value == 1 ? 'checked' : '' ).' />';
    echo '<p><span class="description">'.__( 'Enable or disable reminders functionality.', 'hk-wp-mall' ).'</span></p>';
}
 
function accountSettingsHeader() {
	echo '<h3>'.__( 'Subscribe popup', 'hk-wp-mall' ).'</h3>';
}

function confirmTitle() {
	echo '<h3>'.__( 'Confirmation email', 'hk-wp-mall' ).'</h3>';
}

function confirmUnTitle() {
	echo '<h3>'.__( 'Unsubscribe email', 'hk-wp-mall' ).'</h3>';
}

function confirmGeneral() {
	echo '<h3>'.__( 'E-mail general', 'hk-wp-mall' ).'</h3>';
}

function printModalWelcomeField() {
	echo '<textarea name="hk-reminders[modal-welcome-text]" id="modal-welcome-text" cols="80" rows="8">'.HK_Reminders::getSetting('modal-welcome-text').'</textarea>';
    echo '<p><span class="description">'.__( 'Enter welcome text here to be displayed in the subscribe to list modal popup.', 'hk-wp-mall' ).'</span></p>';
}

function confirmEmailSubject() {
	echo '<input type="text" name="hk-reminders[confirmation-email-subject]" id="confirmation-email-subject" style="width: 572px" value="'.HK_Reminders::getSetting('confirmation-email-subject').'" />';
    echo '<p><span class="description">'.__( 'Enter confirmation email subject here to be sent out to subscribed user.', 'hk-wp-mall' ).'</span></p>';
}

/* Unsubscribe email config fields */
function printConfirmationUnEmail() {
	echo '<textarea name="hk-reminders[confirmation-unemail]" id="confirmation-unemail" cols="80" rows="8">'.HK_Reminders::getSetting('confirmation-unemail').'</textarea>';
    echo '<p><span class="description">'.__( 'Enter unsubscribe email body here to be sent out to unsubscribed user.', 'hk-wp-mall' ).'</span></p>';
}

function confirmEmailUnSubject() {
	echo '<input type="text" name="hk-reminders[confirmation-email-unsubject]" id="confirmation-email-unsubject" style="width: 572px" value="'.HK_Reminders::getSetting('confirmation-email-unsubject').'" />';
    echo '<p><span class="description">'.__( 'Enter unsubscribe email subject here to be sent out to unsubscribed user.', 'hk-wp-mall' ).'</span></p>';
}

/* Subscribe email body field */
function printConfirmationEmail() {
	echo '<textarea name="hk-reminders[confirmation-email]" id="confirmation-email" cols="80" rows="8">'.HK_Reminders::getSetting('confirmation-email').'</textarea>';
    echo '<p><span class="description">'.__( 'Enter confirmation email body here to be sent out to subscribed user. <strong>{unsubscribe_link}</strong> will be replaced with unsubscribe link titled <strong>"click here"</strong>.', 'hk-wp-mall' ).'</span></p>';
}

/* Email footer field */
function printConfirmationEmailFooter() {
	echo '<textarea name="hk-reminders[confirmation-email-footer]" id="confirmation-email-footer" cols="80" rows="8">'.HK_Reminders::getSetting('confirmation-email-footer').'</textarea>';
    echo '<p><span class="description">'.__( 'Enter email footer content here.', 'hk-wp-mall' ).'</span></p>';
}


/**
 * Sanitize settings form values
 * 
 * Add custom message for settings saved message
 *
 * @return Array
 */

function sanitizeSettings( $options ) {
	//Not doing anything here
	return $options;	
}

function createRemindersSettingsPage() {
	echo '<div class="wrap">';
	screen_icon();
	echo '<h2>'.__( 'Reminders settings', 'hk-wp-mall' ).'</h2>';
	echo '<form action="options.php" method="post">';
	settings_fields( 'hk-reminders' );
	do_settings_sections( 'hk-reminders' );
	echo '<input name="Submit" type="submit" id="submit" class="button-primary" value="'.__( 'Save', 'hk-wp-mall' ).'" />';
	echo '</form></div>';
}

add_action( 'admin_init', 'checkForFlagDelete' );

function checkForFlagDelete() {
	if ( isSet( $_POST ) && isSet( $_POST['hk_action'] ) ) {
		if ( $_POST['hk_action'] === 'remove_remindee' ) {
			$remindees = $_POST['remindees'];
			$reminders = new HK_Reminders();
			foreach( $remindees as $remindee ) {
				$reminders->removeRemindee( $remindee );
			}
			header('Location: '.add_query_arg( array( 'page' => 'hk-reminders'), admin_url('admin.php') ));
			die;
		}
		
		if ( $_POST['hk_action'] === 'send_email_reminder' ) {
			$subject = sanitize_text_field( $_POST['hk-reminder-subject'] );
			HK_Reminders::storeTitle( $subject );
			$body = strip_tags( $_POST['hk-reminder-body'] );
			HK_Reminders::storeBody( $body );
			$status_code = 1;
			if ( !empty( $subject ) && !empty( $body ) ) {
				$remindees = HK_Reminders::getAll();
				foreach( $remindees as $remindee ) {
					$msg_out = str_replace( '{unsubscribe_link}', '<a href="'.add_query_arg( array('unsubscribe' => md5( $remindee->email ) ), home_url() ).'">'.__( 'click here', 'hk-wp-mall' ).'</a>', $body );
					$reminder_send_success = HK_Reminders::send_mail( $remindee->email, $subject, $msg_out );
				}
				HK_Reminders::addToHistory( count( $remindees ) );
			}
			if ( empty( $subject ) ) {
				$status_code = 2;
			}
			if ( empty( $body ) ) {
				$status_code = 3;
			}
			header('Location: '.add_query_arg( array( 'page' => 'hk-reminders-send', 'hk_msg' => $status_code ), admin_url('admin.php') ));
			die;
		}
	}
}

function sendEmailNotice() {
	echo '<div class="wrap">';
	if ( isSet($_GET['hk_msg']) ) {
		$msg = false;
		switch( intval( $_GET['hk_msg'] ) ) {
			case 1:
				$msg = __('Reminder email sent', 'hk-wp-mall');
				break;
			case 2:
				$msg = __('Title is empty', 'hk-wp-mall');
				break;
			case 3:
				$msg = __('Body is empty', 'hk-wp-mall');
				break;
		}
		if ( $msg ) {
			echo '<div class="updated">';
			echo '<p><strong>'.$msg.'</strong></p></div>';
		}
	}
	echo '<form id="hk-reminder-send" action="" method="post">';
	echo '<input type="hidden" name="hk_action" value="send_email_reminder" />';
	echo '<h2>'.__( 'Send reminder', 'hk-wp-mall').'</h2>';
	echo '<p>'.sprintf( __( 'E-mail will be sent to <strong>%s subscribers</strong>', 'hk-wp-mall' ), HK_Reminders::getCountByFlag() ).'</p>';
	echo '<p>'.__( 'Last e-mail was sent on ', 'hk-wp-mall' ).' <strong>'.formatDate( HK_Reminders::getLastSentDate() ).'</strong></p>';
	echo '<p><strong>'.__( 'Last sent email content and title is kept by the system', 'hk-wp-mall').'</strong></p>';
	echo '<p><strong>'.__('Subject', 'hk-wp-mall').'</strong></p>';
	echo '<input type="text" name="hk-reminder-subject" style="width: 583px;" value="'.HK_Reminders::getTitle().'" />';
	echo '<p><strong>'.__('Message', 'hk-wp-mall').'</strong></p>';
	echo '<textarea name="hk-reminder-body" cols="80" rows="8">';
	
	if ( $body = HK_Reminders::getBody() ) {
		echo $body;
	} else {
		echo __("Dear ...\n\nYou would like you inform...\n\nDo unsubcribe from the list {unsubscribe_link}", 'hk-wp-mall');
	}
	echo '</textarea>';
	echo '<p><span class="description">'.__( 'Available shortcodes: <strong>{unsubscribe_link}</strong> - will be replaced with unsubcribe link titled <strong>"click here"</strong>.', 'hk-wp-mall' ).' ';
	echo __( '<strong>Footer</strong> is added to the outgoing message <strong>automatically.</strong>', 'hk-wp-mall' );
	echo '</span></p>';
	echo '<p><input type="submit" value="'.__('Send', 'hk-wp-mall').'" /></p>';
	echo '</div>';
}

function sentReminderHistory() {
	echo '<div class="wrap">';
	echo '<h2>'.__( 'History', 'hk-wp-mall' ).'</h2>';
	$history = HK_Reminders::getHistory();
	
	echo '<table class="wp-list-table widefat fixed" cellspacing="0">';
	echo '<thead><tr>
				<th scope="col" class="manage-column"><span>'.__( 'Number of people', 'hk-wp-mall' ).'</span></th><th scope="col" class="manage-column">'.__( 'Date', 'hk-wp-mall' ).'</th>	</tr>
				</thead>';
	echo '<tfoot><tr>
			<th scope="col" class="manage-column"><span>'.__( 'Number of people', 'hk-wp-mall' ).'</span></th><th scope="col" class="manage-column">'.__( 'Date', 'hk-wp-mall' ).'</th>	</tr>
		</tfoot>';
				
	echo '<tbody>';
	
	foreach( $history as $item ) {
			
		echo '<tr class="alternate">
			<td>'.$item->count.'</td>
			<td>'.formatDate( $item->date ).'</td></tr>';
	}
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
}

function createRemindersPage() {
	global $wpdb;
	$remindersHandler = new HK_Reminders();
	
	echo '<div class="wrap">';
	echo '<form action="" method="post">';
	echo '<input type="hidden" name="hk_action" value="remove_remindee" />';
	echo '<h2>'.__( 'Remindees', 'hk-wp-mall' ).'</h2>';
	
	$current_flag = '';
	$flags = $remindersHandler->fetchFlags();
	foreach( $flags as $reminder ) {
		if ( $current_flag != $reminder->flag ) {
			echo '<h3>'.$reminder->flag.' - '.HK_Reminders::getCountByFlag( $reminder->flag ).'</h3>';
			echo '<table class="wp-list-table widefat fixed" cellspacing="0">';
			echo '<thead><tr>
				<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox"></th><th scope="col" class="manage-column"><span>'.__('Email', 'hk-wp-mall' ).'</span></th><th scope="col" class="manage-column"><span>'.__('Flag', 'hk-wp-mall' ).'</span></a></th><th scope="col" class="manage-column column-role" style="">'.__('Date', 'hk-wp-mall' ).'</th>	</tr>
				</thead>';
			echo '<tfoot><tr>
				<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox"></th><th scope="col" class="manage-column"><span>'.__('Email', 'hk-wp-mall' ).'</span></th><th scope="col" class="manage-column"><span>'.__('Flag', 'hk-wp-mall' ).'</span></th><th scope="col" class="manage-column column-role" style="">'.__('Date', 'hk-wp-mall' ).'</th>	</tr>
				</tfoot>';
				
			echo '<tbody id="the-list" class="list:user">';
		}
		foreach( $remindersHandler->fetchByFlag( $reminder->flag ) as $remindee ) {
			echo '<tr id="user-'.$remindee->id.'" class="alternate">
				<th scope="row" class="check-column"><input type="checkbox" name="remindees[]" value="'.$remindee->id.'"></th><td>'.$remindee->email.'</td>
				<td>'.$reminder->flag.'</td>
				<td class="role column-role">'.formatDate( $remindee->date ).'</td></tr>';
		}
		if ( $current_flag != $reminder->flag ) {
				echo '</tbody>';
				echo '</table>';
				echo '<input type="submit" name="" id="doaction2" class="button-secondary action" value="'.__( 'Delete', 'hk-wp-mall' ).'">'; 
		}
	}
	if ( empty( $flags ) ) {
		echo '<p><strong>'.__( 'No subscriptions.', 'hk-wp-mall' ).'</strong></p>';
	}
	echo '</form></div>';
}

/**
 * Register and load some JavaScript
 */

function loadAndRegisterJavaScripts() {	
	if ( is_home() ) {
		wp_register_script( 'jquery-easing', get_template_directory_uri().'/js/jquery.bxSlider/jquery.easing.1.3.js', array( 'jquery' ) );
		wp_register_script( 'jquery-bxSlider', get_template_directory_uri().'/js/jquery.bxSlider/jquery.bxSlider.min.js', array( 'jquery' ) );
		wp_register_script( 'jquery-overflow', get_template_directory_uri().'/js/jquery.hoverflow.min.js', array( 'jquery' ));
		//Load home-script in footer, so ensure proper execution
		wp_register_script( 'hk-home-script', get_template_directory_uri().'/js/home-page.js', array( 'jquery' ), false, true );
		
		wp_enqueue_script( 'jquery-easing' );
		wp_enqueue_script( 'jquery-bxSlider' );
		wp_enqueue_script( 'jquery-overflow' );
		wp_enqueue_script( 'hk-home-script' );
		
		//Check for unsubscribe call
		if ( isSet( $_GET['unsubscribe'] ) ) {
			$exists = HK_Reminders::getUnsubscribe( $_GET['unsubscribe'] );
			if ( $exists ) {
				wp_register_script( 'hk-reminder-unsubscribe', get_template_directory_uri().'/js/hk-reminder-unsubscribe.js', array( 'jquery', 'jquery-ui-dialog') );
				wp_enqueue_script( 'hk-reminder-unsubscribe' );
				$info_array = array( 'reminderUnsubscribed' => __( 'You have been unsubcribed from the mailing list', 'hk-wp-mall' ),
					'reminderClose' => __( 'Close', 'hk-wp-mall' ) );
				wp_localize_script( 'hk-reminder-unsubscribe', 'hk', $info_array );
				HK_Reminders::doUnsubscribe( $exists );
			}
		}
	} else {
		//If not home page, remove accordion menu wp_head action - this disables inserting unneed JavaScript and CSS.
		remove_action('wp_head', 'a_image_menu_head');
	}
	
	wp_register_script( 'hk-general',  get_template_directory_uri().'/js/general.js', array( 'jquery' ) );
	wp_enqueue_script( 'hk-general' );
	
	if ( is_page_template( 'landing-page.php' ) ) {
		wp_enqueue_script( 'jquery-ui-dialog', array( 'jquery' ) );
		wp_register_script( 'landing-page-yt', get_template_directory_uri().'/js/landing.page.yt.js', array( 'swfobject', 'jquery' ) );
		wp_enqueue_script( 'landing-page-yt' );
		
		$translation_array = array( 'videoId' => getLandingPageYT( true ), 'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'reminderSuccess' => __( 'You have successfully subscribed', 'hk-wp-mall' ), 'reminderExist' => __( 'You have already subscribed', 'hk-wp-mall'),
					'reminderEmail' => __( 'Your email is not valid', 'hk-wp-mall' ), 'reminderHack' => __( 'Trying to hack ?', 'hk-wp-mall' ),
					'reminderGeneral' => __( 'Something went wrong, try again', 'hk-wp-mall' ) );
		wp_localize_script( 'landing-page-yt', 'landingPageMeta', $translation_array );
	}
	
	//Load some styles too
	loadAndRegisterCSS();
}

add_action( 'wp_enqueue_scripts', 'loadAndRegisterJavaScripts' );


/**
 * Register scripts and css loading for admin side
 */

function adminScriptsCSS( $hook ) {	
	wp_register_script( 'hk-admin-scripts', get_template_directory_uri().'/js/admin-scripts.js', array( 'jquery' ) );
	wp_enqueue_script( 'hk-admin-scripts' );
	
	$translation_array = array( 'areYouSure' => __('Are you sure?', 'hk-wp-mall'),
				'pickLandingImage' => __('Pick a landing page image', 'hk-wp-mall'),
				'isLandingPage' => editorInLandingPage() ? 'true' : 'false' );
	wp_localize_script( 'hk-admin-scripts', 'hkAdmin', $translation_array );
	
	//Load some admin css styles
	wp_register_style( 'hk-admin-css', get_template_directory_uri(). '/admin-styles.css' );
	wp_enqueue_style( 'hk-admin-css' );
}

add_action( 'admin_enqueue_scripts', 'adminScriptsCSS' );

/**
 * Register and load styles
 */

function loadAndRegisterCSS() {
	wp_register_style( 'print-css', get_template_directory_uri(). '/styles_print.css', false, false, 'print' );
	wp_enqueue_style( 'print-css' );
	
	if ( is_home() ) {
		wp_register_style( 'jquery.bxSlider', get_template_directory_uri(). '/js/jquery.bxSlider/bx_styles/bx_styles.css' );
		wp_enqueue_style( 'jquery.bxSlider' );
	}
	
	if ( is_page_template( 'landing-page.php' ) || isSet( $_GET['unsubscribe'] ) ) {
		wp_register_style( 'jquery.ui.css', get_template_directory_uri(). '/css/ui-custom-theme/jquery-ui-1.8.23.custom.css' );
		wp_enqueue_style( 'jquery.ui.css' );
	}
}

/**
 * Helper method for loading images
 *
 * @return String URL
 */

function getFileURL( $file_name ) {
	return get_template_directory_uri().$file_name;
}

/**
 * Get menu title/name
 *
 * @return String
 */

//If function doesn't exist
if ( !function_exists( 'wp_nav_menu_title' ) ) {
	function wp_nav_menu_title( $theme_location ) {
		$title = '';
		if ( $theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $theme_location ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $theme_location ] );
				
			if( $menu && $menu->name ) {
				$title = $menu->name;
			}
		}
	
		return apply_filters( 'wp_nav_menu_title', $title, $theme_location );
	}
}

/**
 * Build news list
 */

function getLatestNews() {
	$args = array(
		'numberposts'     => 4,
		'orderby'         => 'post_date',
		'order'           => 'DESC',
		'post_type'       => 'post',
		'post_status'     => 'publish' );
	$posts = get_posts( $args );
	
	if ( $posts ) {
		echo '<ul id="latest-news-footer">';
		foreach( $posts as $post ) {
			echo '<li><a href="'.get_permalink( $post->ID ).'" title="'.$post->post_title.'">'.$post->post_title.'</a></li>';
		}
		echo '<li><a href="'.get_year_link('').'">'.__( 'Read more', 'hk-wp-mall').' &#187;</a></li>';
		echo '</ul>';
	} else {
		return false;
	}
}


/**
 * Output Archive left menu
 */
 
function getArchiveLeftMenu() {
	the_widget('WP_Widget_Calendar');
        
	echo '<div class="widget entry-content" style="margin-top: -20px">';
	echo '<ul>';
	wp_get_archives( 'type=yearly&show_post_count=1' );
	echo '</ul>';
	echo '</div>';

	the_widget( 'WP_Widget_Tag_Cloud', array( 'title' => ' ' ) );
	
	echo '<div class="widget">';
	echo '<ul>';
	wp_list_categories('feed_image='.get_bloginfo('template_directory').'/images/rss.png&children=0&exclude=1&show_count&title_li=<h2 class="feeds-title"></h2>');
	echo '</ul>';
	echo '</div>';

}

/**
 * Print pagination HTML for archives
 */
 
function getPaginationHTML() {
	global $wp_query;
	
	echo '<div class="pagination">';
	echo '<div class="pagination-align">';
	$big = 999999999; // need an unlikely integer
	echo paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages
	) );
	echo '</div>';
	echo '</div>';
}


/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
 
function _posted_on() {
	printf( '<div class="posted-on"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></div>',
		esc_url( get_day_link( get_the_date('Y'), get_the_date('m'), get_the_date('d') ) ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
}

function getAllNavMenus(){
    return get_terms( 'nav_menu', array( 'hide_empty' => true ) );
}

/** Some metabox stuff */

add_action( 'add_meta_boxes', 'configurePostMetaboxes' );

function configurePostMetaboxes() {
	if ( editorInLandingPage() ) {
		add_meta_box('landing-page-metabox', __( 'Landing page', 'hk-wp-mall' ),  'landingPageMetabox', 'page', 'normal', 'high');
	}
}

function replace_thickbox_text($translated_text, $text, $domain) {
	if ('Insert into Post' == $text) {
		$referer = strpos( wp_get_referer(), 'hk-landing-page' );
		if ( $referer != '' ) {
			return __('Add to landing page', 'hk-wp-mall' );
		}
	}
	return $translated_text;
}

add_action( 'admin_init', 'landingPageImageSetup' );

function landingPageImageSetup() {
	global $pagenow;
	if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
		// Now we'll replace the 'Insert into Post Button' inside Thickbox
		add_filter( 'gettext', 'replace_thickbox_text'  , 1, 3 );
	}
}

function landingPageMetabox() {
	echo '<p><label for="landing-yt-video" />'.__( 'Youtube URL', 'hk-wp-mall' ).'</label> ';
	echo '<input type="text" name="landing-yt-video" id="landing-yt-video" style="width: 400px" value="'.getLandingPageYT().'"/></p>';
	
	echo '<p>';
	echo '<label for="landing-nav-menu">'.__( 'Pick a landing page menu', 'hk-wp-mall').'</label> ';
	echo '<select name="landing-nav-menu" id="landing-nav-menu">';
	echo '<option value="0">'.__( 'Disabled', 'hk-wp-mall' ).'</option>';
	$prev_menu_id = getLandingPageMenu( get_the_ID(), true );
	foreach( getAllNavMenus() as $nav_menu ) {
		if ( $prev_menu_id == $nav_menu->term_id ) {
			echo '<option value="'.$nav_menu->term_id.'" selected="selected">'.$nav_menu->name.'</option>';
		} else {
			echo '<option value="'.$nav_menu->term_id.'">'.$nav_menu->name.'</option>';
		}
	}
	echo '</select>';
	echo '</p>';
	
	?>
		<p id="imgURL">
			<?php
				if ( has_landing_thumbnail() ) {
					the_landing_thumbnail( 'full' );
				}
			?>
		</p>
		
		<a id="upload_logo_button" href="#" title="<?php _e( 'Pick landing page image', 'hk-wp-mall'); ?>" class="<?php echo ( has_landing_thumbnail() ? 'hide-if-landing-thumbnail' : ''); ?>"><?php _e( 'Pick landing page image', 'hk-wp-mall');?></a>
		<a id="remove_landing_thumbnail" href="#" title="<?php _e( 'Remove landing page image', 'hk-wp-mall'); ?>" class="<?php echo ( has_landing_thumbnail() ? '' : 'hide-if-landing-thumbnail'); ?>"><?php _e( 'Remove landing page image', 'hk-wp-mall'); ?></a>
		
		<input type="hidden" name="landing-image-id" value="<?php echo the_landing_thumbnail_ID(); ?>" id="landing-image-id" />
		
	<?php
}

add_action( 'save_post', 'savePostMetadata', 1, 2 );

function savePostMetadata( $post_id, $post ) {
	if ( $post->post_type == 'page' ) {
		if ( $post->page_template == 'landing-page.php' ) {
			update_post_meta( $post_id, 'landing-nav-menu', sanitize_text_field( $_POST['landing-nav-menu'] ) );
			update_post_meta( $post_id, 'landing-yt-video', sanitize_text_field( $_POST['landing-yt-video'] ) );
			update_post_meta( $post_id, 'landing-image-id', sanitize_text_field( $_POST['landing-image-id'] ) );
		}
	}
}

function getLandingPageMenu( $page_id, $id_only = false ) {
	$menu_id = get_post_meta( $page_id, 'landing-nav-menu', true );
	if ( $id_only ) {
		return $menu_id;
	}
	if ( $menu_id ) {
		wp_nav_menu( array( 'menu' => $menu_id, 'container_class' => 'landing-nav-menu-wrapper', 'menu_class' => 'landing-page-menu', 'fallback_cb' => false, 'walker' => new isDraftMenuWalker() ) );
	}
}

function getLandingPageYT( $id_only = false ) {
	if ( $id_only ) {
		$query_params = parse_url( get_post_meta( get_the_ID(), 'landing-yt-video', true ), PHP_URL_QUERY );
		if ( !is_null( $query_params ) ) {
			$params_array = explode( '&', $query_params );
			foreach( $params_array as $param ) {
				$query_pair = explode( '=', $param );
				if ( $query_pair[0] == 'v' ) {
					return $query_pair[1];
				}
			}
		}
		return false;
	} else {
		return get_post_meta( get_the_ID(), 'landing-yt-video', true );
	}
}

function has_youtube_video() {
	return getLandingPageYT( true ) ? true : false;
}

function the_landing_thumbnail_ID() {
	return get_post_meta( get_the_ID(), 'landing-image-id', true );
}

function the_landing_thumbnail( $size = 'medium' ) {
	echo wp_get_attachment_image( the_landing_thumbnail_ID(), $size, false, array( 'class' => 'wp-post-image landing-image' ) );
}

function has_landing_thumbnail() {
	return the_landing_thumbnail_ID() ?  true : false;
}

function editorInLandingPage() {
	$data = explode( '/', get_page_template() );
	return array_search( 'landing-page.php', $data ) ? true : false;
}


add_action( 'admin_init', 'removePostMetaboxes' );

function removePostMetaboxes() {
    remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
    remove_meta_box( 'authordiv', 'post', 'normal' );
    remove_meta_box( 'postcustom', 'post', 'normal' );
	remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
	remove_meta_box( 'commentsdiv', 'post', 'normal' );
    remove_meta_box( 'formatdiv', 'post', 'normal' );
    
    remove_meta_box( 'commentsdiv', 'page', 'normal' );
    remove_meta_box( 'authordiv', 'page', 'normal' );
    remove_meta_box( 'postcustom', 'page', 'normal' );
    remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
    
}

/*
 * Date formatter method
 */
 
function formatDate( $timestamp, $with_time = false ) {
	if ( $with_time ) {
		return date_i18n( get_option('time_format').' '.get_option('date_format'), strtotime( $timestmap ) );
	} else {
		return date_i18n( get_option('date_format'), strtotime( $timestmap ) );
	}
}

/*
 * the_author and the_author_email filters
 *
 * we only need one author which is TLU Haapsalu College - kolledz@hk.tlu.ee
 *
 * This ensures no matter who creates post, it will be served like college is the author.
 */

add_filter( 'the_author', 'override_author', 10, 1 );

function override_author( $author ) {
	return __('TLU Haapsalu College', 'hk-wp-mall' );
}

add_filter( 'the_author_email', 'override_author_email', 10, 1 );

function override_author_email( $author_email ) {
	return 'kolledz@hk.tlu.ee';
}

?>