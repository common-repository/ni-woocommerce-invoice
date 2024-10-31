<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
include_once('ni-function.php'); 
if( !class_exists( 'ni_woocommerce_invoice' ) ) {
	class ni_invoice extends ni_function
	{
		var $constant_variable = array();
		
		public function __construct($constant_variable = array())
		{
			$this->constant_variable = $constant_variable;
			add_action( 'admin_menu',  array(&$this,'admin_menu' ));
			
			//add_action( 'admin_enqueue_scripts',  array(&$this,'admin_enqueue_scripts' ));
			
			if (isset($_REQUEST["page"])){
			 	$page =  	$this->get_request("page");
			if ($page =="ni-woocommerce-invoice" || $page =="ni-invoice-setting")
		
				add_action( 'admin_enqueue_scripts',  array(&$this,'admin_enqueue_scripts' ));
			}
			
			add_action( 'wp_ajax_ni_action',  array(&$this,'ni_ajax_action' )); /*used in form field name="action" value="my_action"*/
			add_action('admin_init', array( &$this, 'admin_init' ) );
			$this->add_setting_page($constant_variable);
		}
		/*Add Admin Menu*/
		function admin_menu()
		{
		add_menu_page('Ni Invoice','Ni Invoice','manage_options',$this->constant_variable['plugin_menu'],array(&$this,'add_menu_page'),plugins_url( '../images/icon.png', __FILE__ ),55.1);
    	//add_submenu_page($this->constant_variable['plugin_menu'], 'Summary', 'Sales Summary', 'manage_options',$this->constant_variable['plugin_menu'] , array(&$this,'add_menu_page'));
    	add_submenu_page($this->constant_variable['plugin_menu'], 'Order List', 'Order List', 'manage_options', $this->constant_variable['plugin_menu'] , array(&$this,'add_menu_page'));
		
		add_submenu_page($this->constant_variable['plugin_menu'], 'Addons', 'Addons', 'manage_options', 'ni-addons' , array(&$this,'add_menu_page'));
		}
		/*Add page to menu*/
		function add_menu_page()
		{
			$page=$this->get_request("page");
			//echo $page;
			if ($page=="ni-woocommerce-invoice")
			{
				
				include_once("ni-order-list.php");
				$obj =  new ni_order_list();
				$obj->page_init();
				
			}
			if ($page=="ni-order-export")
			{
				include_once("ni-order-summary.php");
				$obj =  new ni_order_summary();
				//$obj->page_init();
				
			}
			if ($page =="ni-addons"){
				include_once("ni-invoice-addons.php");
				$obj =  new ni_invoice_addons();
				$obj->page_init(); 
			}
		}
		function admin_enqueue_scripts(){
			 wp_enqueue_script( 'ajax-script', plugins_url( '../assets/js/ni-order-export-script.js', __FILE__ ), array('jquery') );
			 wp_localize_script( 'ajax-script', 'niinv_ajax_object',array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
			 wp_register_style( 'sales-report-style', plugins_url( '../assets/css/ni-order-export-style.css', __FILE__ ));
			 wp_enqueue_style( 'sales-report-style' );
		}
		function ni_ajax_action()
		{
		   $ni_action_ajax=$this->get_request("ni_action_ajax");
		   //$this->print_data($_REQUEST);
		  // die;
			if($ni_action_ajax =="ni_woocommerce_invoice")
			{
				//$this->print_data($_REQUEST);
				include_once("ni-order-list.php");
				$obj =  new ni_order_list();
				$obj->get_order_list();	
			}
			die;
		}
		function admin_init(){
			if(isset($_REQUEST['ni-order-invoice'])){
				include_once("ni-order-invoice.php");
				
				$today = date_i18n("Y-m-d-H-i-s");				
				$FileName = "woocommerce-order-invoice"."-".$today.".pdf";	
					
				$obj = new ni_order_invoice($this->constant_variable);
				$obj->ni_order_invoice($FileName,"pdf");
				die;
			}
		}
		function add_setting_page($constant_variable)
		{
			include_once("ni-invoice-setting.php");	
			$obj = new ni_invoice_setting($constant_variable);
		}
	}
}
?>