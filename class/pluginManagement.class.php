<?php


class PluginManagement {
	public static function activate() {
      global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql_1 = "CREATE TABLE ".$wpdb->prefix."wcc_contracts (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
		        fkOrder mediumint(9) NOT NULL,
		        fkUser mediumint(9) NOT NULL,
		        createDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		        updateDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		        data text NOT NULL,
		        PRIMARY KEY  (id)
		        ) ".$charset_collate.";";

        $sql_2 = "CREATE TABLE ".$wpdb->prefix."wcc_contracts_image (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
		        fkContracts mediumint(9) NOT NULL,
		        typeImage mediumint(9) NOT NULL,
		        imageValidation mediumint(9) NOT NULL,
    			titleImage varchar (128) DEFAULT '' NOT NULL,	
    			description text NOT NULL,
    			url varchar (512) NOT NULL,
		        createDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		        updateDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		        PRIMARY KEY  (id)
		        ) ".$charset_collate.";";


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_1);
        dbDelta( $sql_2 );
    }
	public static function deactivate(){
		global $wpdb;
		$sql_1 = "DROP TABLE IF EXISTS $wpdb->prefix . 'wcc_contracts'";
		$sql_2 = "DROP TABLE IF EXISTS $wpdb->prefix . 'wcc_contracts_image'";
		$wpdb->query($sql_1);
		$wpdb->query($sql_2);
	}
}