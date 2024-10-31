<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
include_once('ni-function.php'); 
if( !class_exists( 'ni_order_invoice' ) ) {
	class ni_order_invoice extends ni_function{
		var $constant_variable = array();
		
		public function __construct($constant_variable = array())
		{
			$this->constant_variable = $constant_variable;
			
		}
		function ni_order_invoice($file_name="Ni",$file_format="PDF"){
			ob_start();	
			$output = ob_get_contents();
			
			$this->get_invoice_html();
			
			$orientation_pdf 		= isset($_REQUEST['orientation_pdf']) ? $_REQUEST['orientation_pdf'] : "portrait";				
			$paper_size 			= isset($_REQUEST['paper_size']) ? $_REQUEST['paper_size'] : "letter";
			$export_pdf 			= isset($_REQUEST['export_pdf']) ? $_REQUEST['export_pdf'] : "woocommerce-order-invoice";
			$file_name				= $file_name;	
			
			//invoice_order
			$output = ob_get_contents();
			ob_end_clean();
			
			//echo $output;
			//die;
			
			
			$path = dirname($this->constant_variable['plugin_file']);
			//include_once("../dompdf/dompdf_config.inc.php");
			
			require($path."/dompdf/dompdf.php");
			
			//$dompdf = new DOMPDF();	
			
			$dompdf->set_paper($paper_size,$orientation_pdf);
			$dompdf->load_html($output);
			$dompdf->render();
			$dompdf->stream($file_name);
			//die;
		}
		function get_invoice_html()
		{
		 	$options = get_option('ni_invoice_option');
			//echo $options['store_name'];
			//$this->print_data($options);
			$order_id = $_REQUEST["ni-order-invoice"];
		    //echo	$key_2_value = get_post_meta($order_id ,'_order_key',true);
		
		?><html><head><title>Order Invoice</title></head><body>
        	<table cellpadding="0" cellspacing="0" style="width:100%; border:1px solid black;">
        	<tr>
            	<td>
                	<table cellpadding="0" cellspacing="0" style="width:100%; border-bottom:1px solid #000000">
                    	<tr>
                        	<td style="text-align:center"><h1><?php echo $options['store_name'] ?></h1>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<td>
                	<table cellpadding="0" cellspacing="0" style="width:100%; border-bottom:1px solid #000000; padding: 5px 5px 5px 5px;">
                    	<?php
						 $get_post = get_post( $order_id ); 
						//$this->print_data($get_post);
						
						 ?>
                        <tr>
                        	<td>Order Date
                            </td>
                            <td>Order No
                            </td>
                            <td>Inoice  No
                            </td>
                            <td>Inoivde  Date
                            </td> 
                            <td>Order Status
                            </td> 
                            <td>Paymnet Method
                            </td> 
                        </tr>
                        <tr>
                        	<td><?php echo date('Y-m-d', strtotime($get_post->post_date));   ?>
                            </td>
                            <td>#<?php echo $order_id; ?></td>
                            <td>#<?php echo $order_id; ?></td>
                            <td><?php echo date("Y-m-d") ; ?></td> 
                            <td><?php echo ucfirst ( str_replace("wc-","", $get_post->post_status)); ?>
                            </td>
                            <td><?php echo get_post_meta($order_id ,'_payment_method_title',true); ?>
                            </td> 
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<td>
                	<table cellpadding="0" cellspacing="0" style="width:100%; padding: 5px 5px 5px 5px;">
                    	<tr>
                        	<td>
                            	<table style="width:100%">
                                	<tr>
                                    	<td colspan="2"><strong> Billing Details</strong>
                                        </td>
                                    </tr>
                                	<tr>
                                    	<td>Email Address</td>
                                        <td><?php echo get_post_meta($order_id ,'_billing_email',true); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td>Full Name</td>
                                        <td>
											<?php echo get_post_meta($order_id ,'_billing_first_name',true); ?> 
											<?php echo get_post_meta($order_id ,'_billing_last_name',true); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td>Address</td>
                                        <td>
											<?php echo get_post_meta($order_id ,'_billing_address_1',true); ?>
                                            <?php echo get_post_meta($order_id ,'_billing_address_2',true); ?>
                                            <?php echo get_post_meta($order_id ,'_billing_city',true); ?>
                                            <?php echo get_post_meta($order_id ,'_billing_postcode',true); ?>
                                            <?php echo get_post_meta($order_id ,'_billing_state',true); ?>
                                            <?php echo $this->get_country_name(get_post_meta($order_id ,'_billing_country',true)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td>Phone Number</td>
                                        <td><?php echo get_post_meta($order_id ,'_billing_phone',true); ?></td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                            	<table style="width:100%">
                                	<tr>
                                    	<td colspan="2"><strong> Shipping Details</strong></td>
                                    </tr>
                                	<tr>
                                    	<td>Email Address</td>
                                        <td><?php echo get_post_meta($order_id ,'_billing_email',true); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td>Full Name</td>
                                        <td>
											<?php echo get_post_meta($order_id ,'_billing_first_name',true); ?> 
											<?php echo get_post_meta($order_id ,'_billing_last_name',true); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td>Address</td>
                                        <td>
											<?php echo get_post_meta($order_id ,'_billing_address_1',true); ?>
                                            <?php echo get_post_meta($order_id ,'_billing_address_2',true); ?>
                                            <?php echo get_post_meta($order_id ,'_billing_city',true); ?>
                                            <?php echo get_post_meta($order_id ,'_billing_postcode',true); ?>
                                            <?php echo get_post_meta($order_id ,'_billing_state',true); ?>
                                            <?php echo $this->get_country_name(get_post_meta($order_id ,'_billing_country',true)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td>Phone Number</td>
                                        <td><?php echo get_post_meta($order_id ,'_billing_phone',true); ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<td>
                	<table cellpadding="0" cellspacing="0" style="width:100%;">
                     	<tr>
                            <th style="border-bottom: 1px solid #000;border-top: 1px solid #000; padding: 5px 0px 5px 0px;">Product
                            </th>
                            <th style="border-bottom: 1px solid #000;border-top: 1px solid #000; padding: 5px 0px 5px 0px; text-align:right">Price
                            </th>
                            <th style="border-bottom: 1px solid #000;border-top: 1px solid #000; padding: 5px 0px 5px 0px; text-align:right">Quantity
                            </th>
                            <th style="border-bottom: 1px solid #000;border-top: 1px solid #000; padding: 5px 0px 5px 0px; text-align:right">Line Sub Total
                            </th>
                        </tr>
                        <?php  $order_items = $this->get_order_item($order_id); ?>
                        <?php  $line_subtotal = 0;		 ?>
                        <?php 
						$total_item_count = 0;
						$total_item_count = count($order_items);
						?>
                        <?php foreach ($order_items as $k => $v) :?> 
                       
                        <tr>
                        <?php  $line_subtotal += isset($v->line_subtotal)?$v->line_subtotal:0; ?>	
                        	<td style="padding: 0px 0px 0px 5px;"><?php echo $v->order_item_name ?>
                            </td>
                            <td style="text-align:right; padding: 0px 5px 5px 0px;"><?php echo $this->get_niprice($v->line_subtotal/$v->qty); ?>
                            </td>
                            <td style="text-align:right; padding: 0px 5px 5px 0px;"><?php echo $v->qty ?>
                            </td>
                            <td style="text-align:right; padding: 0px 5px 5px 0px;"><?php echo  $this->get_niprice($v->line_subtotal); ?>
                            </td>
                        </tr>
                        <?php endforeach;  ?> 
                        <?php 
						if ($total_item_count<5){
						?>
                        <tr>
                        	<td colspan="4" style="padding-top:200px">
                            </td>
                        </tr>
                        <?php	
						}
						?>
                        
                        
                        <tr>
                            <td colspan="4"  style="border-bottom: 1px solid #000;border-top: 1px solid #000; text-align:right; padding:5px 5px 5px 5px">
                            Subtotal: <?php echo $this->get_niprice($line_subtotal); ?>
                            </td>
                        </tr>
    			    </table>
                </td>
            </tr>
            <tr>
            	<td>
                	<table cellpadding="0" cellspacing="0" style="width:100%;">
                     <?php  $coupon = $this->get_coupon($order_id); ?>
                     <?php if ($coupon) : ?>
                     <tr>
                        	<td style="text-align:right; padding:5px 5px 5px 0px">
                             <?php 
							
							 // $this->print_data($coupon);
							  //echo ucfirst($coupon[0]->order_item_type);
							 // echo $coupon[0]->order_item_name;
							  echo "Discount   - ".$this->get_niprice($coupon[0]->discount_amount);
							 ?>
                            </td>
                        </tr>
					 <?php endif; ?>
                     
                     <?php  $shipping = $this->get_order_shipping($order_id); ?>
                     <?php if ($shipping) : ?>
                     <tr>

                        	<td  style="text-align:right; padding:5px 5px 5px 0px">
                             <?php 
							
							  //$this->print_data($shipping);
							  //shipping
							  //echo ucfirst($shipping[0]->order_item_type);
							  
							  echo "Shipping (". $shipping[0]->order_item_name .") ". $this->get_niprice($shipping[0]->cost);
							 ?>
                            </td>
                        </tr>
                     <?php endif; ?>
                     <?php  $tax = $this->get_order_tax($order_id); ?>
                     <?php if ($tax) : ?>
                     <tr>
                        	<td style="text-align:right; padding:5px 5px 5px 0px">
                             <?php 
							
							  //$this->print_data($shipping);
							  //echo ucfirst($tax[0]->order_item_type);
							  //echo $tax[0]->order_item_name;
							  echo $tax[0]->order_item_name ." ". $this->get_niprice($tax[0]->tax);
							 ?>
                            </td>
                        </tr>
					 <?php endif; ?>
                    </table>
                </td>
            </tr>
            <tr>
            	<td style="text-align:right; padding:5px 5px 5px 0px; border-bottom:1px solid #000;border-top:1px solid #000">
                	Total <?php  echo  $this->get_niprice(get_post_meta( $order_id, '_order_total' , true)); ?>
                </td>
            </tr>
            <tr>
            	<td>
                	<table cellpadding="0" cellspacing="0" style="width:100%;padding:5px 5px 5px 5px">
                    	<tr>
                        	<td><?php echo $options['footer_notes']; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table></body></html><?php
		}
		function get_order_item($order_id=NULL)
		{	global $wpdb;
			
			$query = "SELECT 
						woocommerce_order_items.order_id as order_id
						,woocommerce_order_items.order_item_id
						,woocommerce_order_items.order_item_name
						,qty.meta_value as qty 
						,line_subtotal.meta_value as line_subtotal 
					
					FROM {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as qty ON qty.order_item_id=woocommerce_order_items.order_item_id ";
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as line_subtotal ON line_subtotal.order_item_id=woocommerce_order_items.order_item_id ";
				
			
			$query .= " WHERE 1=1";
			$query .= " AND woocommerce_order_items.order_id={$order_id}";
			$query .= " AND woocommerce_order_items.order_item_type='line_item'";
			$query .= " AND qty.meta_key='_qty'";
			$query .= " AND line_subtotal.meta_key='_line_subtotal'";
			
			
			
			$results = $wpdb->get_results( $query);	
			//$this->print_data($results);
			
			return $results;
		
		}
		function get_order_shipping($order_id=NULL){
			global $wpdb;
			$query = " SELECT *
						,woocommerce_order_items.order_item_name
						,woocommerce_order_items.order_item_name
						,cost.meta_value as cost 	
						 FROM {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ";
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as cost ON cost.order_item_id=woocommerce_order_items.order_item_id ";
						 
			$query .= " WHERE 1=1";
			$query .= " AND woocommerce_order_items.order_id={$order_id}";
			$query .= " AND woocommerce_order_items.order_item_type='shipping'";
			$query .= " AND cost.meta_key='cost'";
			
			$results = $wpdb->get_results( $query);	
			
			return $results;
		}
		function get_coupon($order_id=NULL)
		{
			global $wpdb;
			$query = " SELECT *
						,woocommerce_order_items.order_item_name
						,woocommerce_order_items.order_item_name
						,discount_amount.meta_value as discount_amount 	
						 FROM {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ";
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as discount_amount ON discount_amount.order_item_id=woocommerce_order_items.order_item_id ";
						 
			$query .= " WHERE 1=1";
			$query .= " AND woocommerce_order_items.order_id={$order_id}";
			$query .= " AND woocommerce_order_items.order_item_type='coupon'";
			$query .= " AND discount_amount.meta_key='discount_amount'";
			
			$results = $wpdb->get_results( $query);	
			
			return $results;
		}
		function get_order_tax($order_id=NULL)
		{
			global $wpdb;
			$query = " SELECT *
						,woocommerce_order_items.order_item_name
						,woocommerce_order_items.order_item_name
						,tax.meta_value as tax 	
						 FROM {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ";
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as tax ON tax.order_item_id=woocommerce_order_items.order_item_id ";
						 
			$query .= " WHERE 1=1";
			$query .= " AND woocommerce_order_items.order_id={$order_id}";
			$query .= " AND woocommerce_order_items.order_item_type='tax'";
			$query .= " AND tax.meta_key='tax_amount'";
			
			$results = $wpdb->get_results( $query);	
			
			return $results;
		}	
	}
}
?>