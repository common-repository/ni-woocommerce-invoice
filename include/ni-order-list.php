<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
include_once('ni-function.php'); 
if( !class_exists( 'ni_order_list' ) ) {
	class ni_order_list extends ni_function{
		public function __construct(){
			
		}
		public function page_init(){
		?>
        <form id="ni_frm_order_export" class="ni-frm-order-export" name="ni_frm_order_export" action="" method="post">
            <table>
                <tr>
                    <td>Select Order</td>
                    <td><select name="select_order" id="select_order">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="last_7_days">Last 7 days</option>	
                    <option value="last_30_days">Last 30 days</option>
                    <option value="this_year">This year</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:right"><input type="submit" value="Search" id="SearchOrder" /></td>
                </tr>
            </table>
            <input type="hidden" name="action" value="ni_action" />
            <input type="hidden" name="ni_action_ajax" value="ni_woocommerce_invoice" />
        </form>
        <div class="ajax_content"></div>
        <?php
			
		}
		function get_order_query($type="DEFAULT"){
			global $wpdb;	
			$today = date("Y-m-d");
	    	$select_order = $this->get_request("select_order");
			
			$query = "SELECT 
				posts.ID as order_id
				,post_status as order_status
				, date_format( posts.post_date, '%Y-%m-%d') as order_date 
				FROM {$wpdb->prefix}posts as posts			
				WHERE 
						posts.post_type ='shop_order' 
						";
				 switch ($select_order) {
					case "today":
						$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$today}' AND '{$today}'";
						break;
					case "yesterday":
						$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') = date_format( DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d')";
						break;
					case "last_7_days":
						$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 7 DAY), '%Y-%m-%d') AND   '{$today}' ";
						break;
					case "last_30_days":
							$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 30 DAY), '%Y-%m-%d') AND   '{$today}' ";
						break;	
					case "this_year":
						$query .= " AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(CURDATE(), '%Y-%m-%d'))";			
						break;		
					default:
						$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$today}' AND '{$today}'";
				}
			$query .= "order by posts.post_date DESC";	
			
		 if ($type=="ARRAY_A") /*Export*/
		 	$results = $wpdb->get_results( $query, ARRAY_A );
		 if($type=="DEFAULT") /*default*/
		 	$results = $wpdb->get_results( $query);	
		 if($type=="COUNT") /*Count only*/	
		 	$results = $wpdb->get_var($query);		
			//echo $query;
			//echo mysql_error();
		//	$this->print_data($results);
			return $results;	
		}
		/*get_order_list*/
		function get_order_list()
		{
			$this->get_order_grid();	
			
			
		}
		function get_order_data()
		{
			$order_query = $this->get_order_query("DEFAULT");
			
		
			if(count($order_query)> 0){
				foreach($order_query as $k => $v){
					
					/*Order Data*/
					$order_id =$v->order_id;
					$order_detail = $this->get_order_detail($order_id);
					foreach($order_detail as $dkey => $dvalue)
					{
							$order_query[$k]->$dkey =$dvalue;
						
					}
				}
			}
			else
			{
				echo "No Record Found";
			}
			return $order_query;
		}
		function get_order_grid()
		{
			$order_total = 0;
			$order_data = $this->get_order_data();
			
			//$this->print_data ($order_data);
			
			if(count($order_data)> 0)
			{
				?>
               <div class="data-table">
				<table>
					<tr>
						<th>#ID</th>
						<th>Order Date</th>
						<th>Billing First Name</th> 
						<th>Billing Email</th> 
						<th>Billing Country</th> 
						<th>Status</th>
                        <th>Order Currency</th>
						<th>Order Total</th>
                        <th>Invoice</th>
					</tr>
				
				<?php
				foreach($order_data as $k => $v){
					$order_total += isset($v->order_total)?$v->order_total:0;
				?>
					<tr>
						<td> <?php echo $v->order_id;?> </td>
						<td> <?php echo $v->order_date;?> </td>
						<td> <?php echo isset($v->billing_first_name)?$v->billing_first_name:"";?> </td>
						<td> <?php echo isset( $v->billing_email)? $v->billing_email:"";?> </td>
						<td> <?php echo $this->get_country_name(isset($v->billing_country)?$v->billing_country:"");?> </td>
                       	<td> <?php echo ucfirst ( str_replace("wc-","", isset($v->order_status)?$v->order_status:""));?> </td>
                        <td> <?php echo isset( $v->order_currency)? $v->order_currency:"";?> </td>
						<td style="text-align:right"> <?php echo $this->get_niprice(isset($v->order_total)?$v->order_total:0);?> </td>
                        
                        <td> <a href="<?php echo admin_url("admin.php?page=ni-woocommerce-invoice")."&ni-order-invoice=". $v->order_id  ; ?>">Invoice</a></td>
					</tr>	
				<?php }?>
				</table>
                <div style="text-align:right; margin-top:10px">
                	<?php  echo $this->get_niprice($order_total); ?>
                </div>
				<?php
				
				//$this->print_data(	$order_data );
			}
		}
		/*Get Order Header information*/
		function get_order_detail($order_id)
		{
			$order_detail	= get_post_meta($order_id);
			$order_detail_array = array();
			foreach($order_detail as $k => $v)
			{
				$k =substr($k,1);
				$order_detail_array[$k] =$v[0];
			}
			return 	$order_detail_array;
		}
	}
}
?>