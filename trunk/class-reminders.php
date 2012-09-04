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
	
	private static function getHistoryTable() {
		global $wpdb;
		return $wpdb->prefix . 'hk_reminders_history';
	}
	
	public static function getCountByFlag( $flag = '') {
		global $wpdb;
		if ( !empty( $flag ) ) {
			return $wpdb->get_var( $wpdb->prepare( 'SELECT count(*) FROM '.self::getTableName().' WHERE flag = %s', $flag ) );
		} else {
			return $wpdb->get_var( 'SELECT DISTINCT count(*) FROM '.self::getTableName().' GROUP BY email' );
		}
	}
	
	public static function getAll() {
		global $wpdb;
		return $wpdb->get_results( 'SELECT DISTINCT email FROM '.self::getTableName() );
	}
	
	public static function addToHistory( $count = 0 ) {
		global $wpdb;
		return $wpdb->insert( self::getHistoryTable(), array( 'count' => $count ), array( '%d' ) );
	}
	
	public static function getHistory() {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( 'SELECT count, date FROM '.self::getHistoryTable() ) );
	}
	
	public static function getLastSentDate() {
		global $wpdb;
		return $wpdb->get_var( 'SELECT date FROM '.self::getHistoryTable().' ORDER BY id DESC LIMIT 1 ');
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