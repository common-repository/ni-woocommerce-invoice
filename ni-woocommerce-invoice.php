<?php 
/*
Plugin Name: Ni WooCommerce Invoice
Description: Ni WooCommerce invoice plugin generate the woocommerce sales order invoice PDF. This plug-in also provide the option to filter date wise sales order and setting option to allow the change of store name or company name and footer notes.
Version: 1.5.7
Author: anzia
Author URI: http://naziinfotech.com/
Plugin URI: https://wordpress.org/plugins/ni-woocommerce-invoice/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/agpl-3.0.html
Requires at least: 4.7
Tested up to: 6.1.1
WC requires at least: 3.0.0
WC tested up to: 7.3.0
Last Updated Date: 13-February-2023
Requires PHP: 7.0
*/
include_once('include/ni-invoice.php'); 

$constant_variable = array(
	'plugin_name' => 'Ni Woocommerce Invoice',
	'plugin_role' => 'manage_options',
	'plugin_key'  => 'ni_woocommerce_invoice',
	'plugin_menu' => 'ni-woocommerce-invoice',
	"plugin_file" 			=> __FILE__
);
$GLOBALS['ni_invoice'] = new ni_invoice($constant_variable );
?>