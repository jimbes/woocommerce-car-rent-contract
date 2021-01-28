<?php

add_action( 'admin_enqueue_scripts', 'load_admin_style' );
function load_admin_style() {
	wp_enqueue_style( 'admin_css_cart_rent_contract', plugins_url('/assets/css/style_admin.css', __FILE__));
	wp_enqueue_script('admin_js_cart_rent_contract', plugins_url('/assets/js/contract_admin.js', __FILE__),array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script('admin_js_pdf', plugins_url('/assets/js/html2pdf.bundle.min.js', __FILE__),array( 'jquery' ), '1.0.0', true );
}