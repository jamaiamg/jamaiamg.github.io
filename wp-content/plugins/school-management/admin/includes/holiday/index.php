<?php 
	// This is Class at admin side!!!!!!!!! 
	
	//----------------delete record-------------------
		$tablename="holiday";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=delete_holiday($tablename,$_REQUEST['holiday_id']);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Record successfully deleted!','school-mgt');?></p>
					</div>
	  <?php }
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=delete_holiday($tablename,$id);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
	//----------------add and update code------------
			if(isset($_POST['save_holiday']))
	{
		$haliday_data=array('holiday_title'=>$_POST['holiday_title'],
						'description'=>$_POST['description'],
						'date'=>date('Y-m-d', strtotime($_POST['date'])),
						'end_date'=>date('Y-m-d', strtotime($_POST['end_date'])),
						'created_by'=>get_current_user_id()
						);
		//table name without prefix
		$tablename="holiday";
		
		if($_REQUEST['action']=='edit')
		{
			$holiday_id=array('holiday_id'=>$_REQUEST['holiday_id']);
			
			
			$result=update_record($tablename,$haliday_data,$holiday_id);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Record successfully Updated!','school-mgt');?></p>
					</div>
	  <?php }
		}
		else
		{
			$result=insert_record($tablename,$haliday_data);
			
				if($result)
				{?>
					<div id="message" class="updated below-h2">
							<p><?php _e('Record successfully inserted!','school-mgt');?></p>
						</div>
		  <?php }
		}
		
		
	}

	//---------------------------
$active_tab = isset($_GET['tab'])?$_GET['tab']:'holidaylist';
	?>
<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>

<div  id="main-wrapper" class=" holidays_list">  
	<div class="panel panel-white">
					<div class="panel-body">    
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_holiday&tab=holidaylist" class="nav-tab <?php echo $active_tab == 'holidaylist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Holiday List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
       <a href="?page=smgt_holiday&tab=addholiday&action=edit&notice_id=<?php echo $_REQUEST['holiday_id'];?>" class="nav-tab <?php echo $active_tab == 'addholiday' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Holiday', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_holiday&tab=addholiday" class="nav-tab <?php echo $active_tab == 'addholiday' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Holiday', 'school-mgt'); ?></a>  
        <?php } ?>
    </h2>
    <?php
	
	if($active_tab == 'holidaylist')
	{	
	?>	
   		
    	
         <?php 
		 $retrieve_class = get_all_data($tablename);
		?>
        <div class="panel-body">
		<script>
jQuery(document).ready(function() {
	var table =  jQuery('#holiday_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                  
	                  {"bSortable": false},
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
        <table id="holiday_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>  
				<th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>              
               <th><?php _e('Holiday Title','school-mgt');?></th>
                <th><?php _e('Description','school-mgt');?></th>
                <th><?php _e('Start Date','school-mgt');?></th>
                <th><?php _e('End Date','school-mgt');?></th>                 
                <th><?php _e('Action','school-mgt');?></th>             
            </tr>
        </thead>
 
        <tfoot>
            <tr>
			<th></th>
             <th><?php _e('Holiday Title','school-mgt');?></th>
                <th><?php _e('Description','school-mgt');?></th>
                <th><?php _e('Start Date','school-mgt');?></th>
                <th><?php _e('End Date','school-mgt');?></th>               
                <th><?php _e('Action','school-mgt');?></th>            
            </tr>
        </tfoot>
 
        <tbody>
          <?php 	
		 	foreach ($retrieve_class as $retrieved_data){ 		
		 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->holiday_id;?>"></td>
                <td><?php echo $retrieved_data->holiday_title;?></td>
                <td><?php echo $retrieved_data->description;?></td>
                <td><?php echo smgt_getdate_in_input_box($retrieved_data->date);?></td>
                 <td><?php echo smgt_getdate_in_input_box($retrieved_data->end_date);?></td>
                   
               <td><a href="?page=smgt_holiday&tab=addholiday&action=edit&holiday_id=<?php echo $retrieved_data->holiday_id;?>"class="btn btn-info"><?php _e('Edit','school-mgt');?></a>
               <a href="?page=smgt_holiday&tab=holidaylist&action=delete&holiday_id=<?php echo $retrieved_data->holiday_id;?>" class="btn btn-danger" 
               onclick="return confirm('<?php _e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php _e('Delete','school-mgt');?></a></td>
            </tr>
            <?php } ?>
     
        </tbody>
        
        </table>
		<div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php _e('Delete Selected','school-mgt');?>" name="delete_selected" class="btn btn-danger delete_selected"/>
			
		</div>
		</form>
        </div>
        </div>
       
     <?php 
	 }
	if($active_tab == 'addholiday')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/holiday/add-holiday.php';
		
	 }
	 ?>
	 		</div>
	 	</div>
	 </div>
</div>
<?php ?>