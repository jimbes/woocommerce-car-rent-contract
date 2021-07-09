<?php
/**
 * Plugin Name: Woocommerce Car rent Contract
 * Plugin URI: http://www.jimmy-besse.fr/woocommerce-car-rent-contract
 * Description: Ability to create contract from Woocommerce Order.
 * Version: 1.5
 * Author: Jimmy Besse
 * Author URI: http://www.jimmy-besse.fr
 */

require_once plugin_dir_path(__FILE__) . '/class/pluginManagement.class.php';
require_once plugin_dir_path(__FILE__) . '/class/Contract.class.php';
require_once plugin_dir_path(__FILE__) . '/class/ContractImages.class.php';

require_once plugin_dir_path(__FILE__) . '/admin/listContractAdmin.php';
require_once plugin_dir_path(__FILE__) . '/admin/detailContractAdmin.php';

require_once plugin_dir_path(__FILE__) . '/admin/menu.php';
require_once plugin_dir_path(__FILE__) . '/admin/functions.php';

//ActivationPlugin
register_activation_hook( __FILE__, "createDB" );
register_deactivation_hook( __FILE__, "deleteDB" );
function createDB(){
PluginManagement::activate();
}
function deleteDB(){
PluginManagement::deactivate();
}