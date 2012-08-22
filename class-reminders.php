<?php

class HK_Reminders {
	private $table_name = false;

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
	
	function checkIfThisYearReminder( $email, $flag ) {
		global $wpdb;
		$result = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$this->table_name.' WHERE email = %s AND flag = %s AND YEAR(date) = YEAR(NOW())', $email, $flag ) );
		if ( !is_null( $result ) ) {
			return true;
		}
		return false;
	}
}

?>