<?php

//TODO fully convert into static class

class HK_Reminders {
	private static $table_name = false;

	function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix.'hk_reminders';
	}
	
	function fetchFlags() {
		global $wpdb;
		return $wpdb->get_results( 'SELECT DISTINCT flag FROM '.$this->table_name.' ORDER BY flag ASC' );
	}
	
	function fetchByFlag( $flag ) {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM '.$this->table_name.' WHERE flag = "%s" ORDER BY email ASC', $flag ) );
	}
	
	function addReminder( $email, $flag ) {
		global $wpdb;
		if ( !$this->checkIfThisYearReminder( $email, $flag ) ) {
			return $wpdb->insert( $this->table_name, array( 'email' => $email, 'flag' => $flag ), array( '%s', '%s' ) );
		} else {
			return false;
		}
	}
	
	function removeRemindee( $id = false ) {
		if ( intval( $id ) > 0 ) {
			global $wpdb;
			return $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$this->table_name.' WHERE id = %d', $id ) );
		}
		return false;
	}
	
	private static function getTableName() {
		global $wpdb;
		return $wpdb->prefix.'hk_reminders';
	}
	
	public static function getCountByFlag( $flag = '') {
		if ( !empty( $flag ) ) {
			global $wpdb;
			return $wpdb->get_var( $wpdb->prepare( 'SELECT count(*) FROM '.self::getTableName().' WHERE flag = %s', $flag ) );
		}
		return 0;
	}
	
	function checkIfThisYearReminder( $email, $flag ) {
		global $wpdb;
		$result = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$this->table_name.' WHERE email = %s AND flag = %s AND YEAR(date) = YEAR(NOW())', $email, $flag ) );
		if ( !is_null( $result ) ) {
			return true;
		}
		return false;
	}
	
	public static function getSetting( $setting = '' ) {
		if ( !empty( $setting ) ) {
			$data = get_option('hk-reminders');
			if ( isSet( $data[$setting] ) ) {
				return $data[$setting];
			}
		}
		return false;
	}
}

?>