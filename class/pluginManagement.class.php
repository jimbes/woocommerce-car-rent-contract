<?php


class PluginManagement {
	public static function activate() {
      global $wpdb;
        $table = $wpdb->prefix . 'wcc_contracts';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
		        fkOrder mediumint(9) NOT NULL,
		        fkUser mediumint(9) NOT NULL,
		        createDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		        updateDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		        data text NOT NULL,
		        PRIMARY KEY  (id)
		        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
	public static function deactivate(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'wcc_contracts';
		$sql = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query($sql);
	}
}