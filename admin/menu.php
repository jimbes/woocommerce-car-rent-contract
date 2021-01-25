<?php


add_action('admin_menu', 'addAdminMenu');
function addAdminMenu() {
	add_menu_page(
		'Liste des contrats',
		'Contrats',
		'manage_options',
		'car_rent_contracts',
		'listContractAdminFunction'
	);
	add_submenu_page(
		'car_rent_contracts',
		'Détail d\'un contrat',
		'Détail',
		'manage_options',
		'car_rent_contract_detail',
		'detailContractAdminFunction'
	);

}