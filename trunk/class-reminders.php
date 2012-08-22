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
}

?>