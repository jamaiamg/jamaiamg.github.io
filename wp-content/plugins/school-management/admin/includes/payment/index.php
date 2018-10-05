<?php 
	// This is Class at admin side!!!!!!!!! 
	//---------delete record-----------------
	$tablename="smgt_payment";
	$obj_invoice= new Smgtinvoice();
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
	if(isset($_REQUEST['payment_id'])){
	$result=delete_payment($tablename,$_REQUEST['payment_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=payment&message=3');
		}
	}
	if(isset($_REQUEST['income_id'])){
		$result=$obj_invoice->delete_income($_REQUEST['income_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=3');
		}
	}
	if(isset($_REQUEST['expense_id'])){
		$result=$obj_invoice->delete_expense($_REQUEST['expense_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=3');
		}
	}
	}
	if(isset($_REQUEST['delete_selected_payment']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
				$result=delete_payment($tablename,$id);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
	if(isset($_REQUEST['delete_selected_income']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
				$result=$obj_invoice->delete_income($id);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
	if(isset($_REQUEST['delete_selected_expense']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
				$result=$obj_invoice->delete_expense($id);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
	
	//----------update and delete record---------------------
			if(isset($_POST['save_payment']))
			{
				$section_id=0;
				if(isset($_POST['class_section']))
					$section_id=$_POST['class_section'];	
		$created_date = date("Y-m-d H:i:s");
		$payment_data=array('student_id'=>$_POST['student_id'],
						'class_id'=>$_POST['class_id'],
						'section_id'=>$section_id,
						'payment_title'=>$_POST['payment_title'],
						'description'=>$_POST['description'],
						'amount'=>$_POST['amount'],
						'payment_status'=>$_POST['payment_status'],
						'date'=>$created_date,					
						'payment_reciever_id'=>get_current_user_id()
						
						
		);
		
		$tablename="smgt_payment";
		if($_REQUEST['action']=='edit')
		{
			$transport_id=array('payment_id'=>$_REQUEST['payment_id']);
			
			
			$result=update_record($tablename,$payment_data,$transport_id);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Record successfully Updated!','school-mgt');?></p>
					</div>
	  <?php }
		}
		else
		{
			$result=insert_record($tablename,$payment_data);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('record successfully inserted!','school-mgt');?></p>
					</div>
	  <?php }
				
		}
	}
	
	if(isset($_POST['save_income']))
	{
			
		if($_REQUEST['action']=='edit')
		{
	
			$result=$obj_invoice->add_income($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=2');
			}
		}
		else
		{
			$result=$obj_invoice->add_income($_POST);
			
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=1');
			}
		}
			
	}
	
	//--------save Expense-------------
	if(isset($_POST['save_expense']))
	{
			
		if($_REQUEST['action']=='edit')
		{
	
			$result=$obj_invoice->add_expense($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=2');
			}
		}
		else
		{
			$result=$obj_invoice->add_expense($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=1');
			}
		}
			
	}
	
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
			{?>
					<div id="message" class="updated below-h2 ">
					<p>
					<?php 
						_e('Record inserted successfully','school-mgt');
					?></p></div>
					<?php 
				
			}
			elseif($message == 2)
			{?><div id="message" class="updated below-h2 "><p><?php
						_e("Record updated successfully",'school-mgt');
						?></p>
						</div>
					<?php 
				
			}
			elseif($message == 3) 
			{?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				_e('Record deleted successfully','school-mgt');
			?></div></p><?php
					
			}
		}	

$active_tab = isset($_GET['tab'])?$_GET['tab']:'payment';
	?>
	<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="invoice_data">
     </div>
     
    </div>
    </div> 
    
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div  id="main-wrapper" class=" payment_list"> 
	<div class="panel panel-white">
					<div class="panel-body">     
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_payment&tab=payment" class="nav-tab <?php echo $active_tab == 'payment' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Payment List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'addpayment')
		{?>
       <a href="?page=smgt_payment&tab=addpayment&action=edit&payment_id=<?php echo $_REQUEST['payment_id'];?>" class="nav-tab <?php echo $active_tab == 'addpayment' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Payment', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_payment&tab=addpayment" class="nav-tab <?php echo $active_tab == 'addpayment' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Payment', 'school-mgt'); ?></a>  
        <?php } ?>
        
       <a href="?page=smgt_payment&tab=incomelist" class="nav-tab <?php echo $active_tab == 'incomelist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Income List', 'school-mgt'); ?></a>
		 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['income_id']))
		{?>
        <a href="?page=smgt_payment&tab=addincome&action=edit&income_id=<?php echo $_REQUEST['income_id'];?>" class="nav-tab <?php echo $active_tab == 'addincome' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Income', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=smgt_payment&tab=addincome" class="nav-tab <?php echo $active_tab == 'addincome' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Income', 'school-mgt'); ?></a>  
		<?php  }?>
		<a href="?page=smgt_payment&tab=expenselist" class="nav-tab <?php echo $active_tab == 'expenselist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Expense List', 'school-mgt'); ?></a>
		 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['expense_id']))
		{?>
        <a href="?page=smgt_payment&tab=addexpense&action=edit&expense_id=<?php echo $_REQUEST['expense_id'];?>" class="nav-tab <?php echo $active_tab == 'addexpense' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Expense', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=smgt_payment&tab=addexpense" class="nav-tab <?php echo $active_tab == 'addexpense' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Expense', 'school-mgt'); ?></a>  
		<?php  }?>
    </h2>
    <?php
	
	if($active_tab == 'payment')
	{	
	?>	
	 <?php 
		 	$retrieve_class = get_payment_list();
			
			
			
		?>
		 <div class="panel-body">
		  <script>
jQuery(document).ready(function() {
	var table =  jQuery('#payment_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                  
	                  {"bSortable": false},	                 
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                 	                  
	                  {"bSortable": true},	                 	                  
	                  {"bSortable": true},	                 	                  
	                  {"bSortable": true},	                 	                  
	                 	                 	                  
	                  {"bSortable": false}],
		language:<?php echo smgt_datatable_multi_language();?>
    });
	 jQuery('#checkbox-select-all').on('click', function(){
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
      jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
   }); 
   
	jQuery('#delete_selected').on('click', function(){
		 var c = confirm('Are you sure to delete?');
		if(c){
			jQuery('#frm-example').submit();
		}
		
	});
   
});

</script>
        <div class="table-responsive">
		<form id="frm-example" name="frm-example" method="post">
        <table id="payment_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>      
				 <th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>             
                <th><?php _e('Student Name','school-mgt');?></th>
                <th><?php _e('Roll No.','school-mgt');?></th>
                <th><?php _e('Class','school-mgt');?> </th>
                <th><?php _e('Title','school-mgt');?></th>
               
                <th><?php _e('Amount','school-mgt');?></th>
                <th><?php _e('Status','school-mgt');?></th>
                 <th><?php _e('Date','school-mgt');?></th>
                <th><?php _e('Action','school-mgt');?></th>             
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
            	 <th><?php _e('Student Name','school-mgt');?></th>
             	 <th><?php _e('Roll No.','school-mgt');?></th>
                <th><?php _e('Class','school-mgt');?> </th>
                <th><?php _e('Title','school-mgt');?></th>
               
                <th><?php _e('Amount','school-mgt');?></th>
                <th><?php _e('Status','school-mgt');?></th>
                 <th><?php _e('Date','school-mgt');?></th>
                <th><?php _e('Action','school-mgt');?></th>   
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
			
		 	foreach ($retrieve_class as $retrieved_data){ 
			
		 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->payment_id;?>"></td>
                <td><?php echo get_user_name_byid($retrieved_data->student_id);?></td>
                <td><?php echo get_user_meta($retrieved_data->ID, 'roll_id',true);?></td>
                <td><?php echo get_class_name($retrieved_data->class_id);?></td>
                <td><?php echo $retrieved_data->payment_title;?></td>
               
                  <td><?php echo $retrieved_data->amount;?></td>
                   <td><?php if($retrieved_data->payment_status=='Paid') 
								echo __('Paid','school-mgt');
							elseif($retrieved_data->payment_status=='Part Paid')
							 	echo __('Part Paid','school-mgt');
							else
								echo __('Unpaid','school-mgt');	?></td>
                <td><?php  echo smgt_getdate_in_input_box($retrieved_data->date);?></td>         
               <td>
               <a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->payment_id; ?>" invoice_type="invoice">
				<i class="fa fa-eye"></i> <?php _e('View Income', 'school-mgt');?></a>
               <a href="?page=smgt_payment&tab=addpayment&action=edit&payment_id=<?php echo $retrieved_data->payment_id;?>" class="btn btn-info"><?php _e('Edit','school-mgt');?></a>
               <a href="?page=smgt_payment&tab=payment&action=delete&payment_id=<?php echo $retrieved_data->payment_id;?>" class="btn btn-danger"
               onclick="return confirm('<?php _e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php _e('Delete','school-mgt');?></a></td>
            </tr>
            <?php } ?>
     
        </tbody>
        
        </table>
		<div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php _e('Delete Selected','school-mgt');?>" name="delete_selected_payment" class="btn btn-danger delete_selected"/>
			
		</div>
		</form>
       </div></div>
     <?php 
	 }
	if($active_tab == 'addpayment')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/payment/add-payment.php';
		
	 }
	 if($active_tab == 'incomelist')
	 {
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/payment/income-list.php';
	 }
	 if($active_tab == 'addincome')
	 {
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/payment/add_income.php';
	 }
	 if($active_tab == 'expenselist')
	 {
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/payment/expense-list.php';
	 }
	 if($active_tab == 'addexpense')
	 {
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/payment/add_expense.php';
	 }
	 ?>
	 		</div>
	 	</div>
	 </div>
</div>
<?php ?>