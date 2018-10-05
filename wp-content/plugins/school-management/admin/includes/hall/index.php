<?php 
	// This is Class at admin side!!!!!!!!! 
	
	//---------delete record--------------------
	$tablename="hall";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=delete_hall($tablename,$_REQUEST['hall_id']);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('record successfully deleted!','school-mgt');?></p>
					</div>
	  <?php }
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
				$result=delete_hall($tablename,$id);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
	//----------insert and update--------------------
	if(isset($_POST['save_hall']))
	{
		$created_date = date("Y-m-d H:i:s");
		$hall_data=array('hall_name'=>$_POST['hall_name'],
						'number_of_hall'=>$_POST['number_of_hall'],
						'hall_capacity'=>$_POST['hall_capacity'],
						'description'=>$_POST['description'],
						'date'=>$created_date
						);
		//table name without prefix
		$tablename="hall";
		
		if($_REQUEST['action']=='edit')
		{
			$transport_id=array('hall_id'=>$_REQUEST['hall_id']);
			$result=update_record($tablename,$hall_data,$transport_id);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('record successfully Updated!','school-mgt');?></p>
					</div>
	  <?php }
		}
		else
		{
			$result=insert_record($tablename,$hall_data);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('record successfully inserted!','school-mgt');?></p>
					</div>
	  <?php }
				
		}
		
		
	}

	
$active_tab = isset($_GET['tab'])?$_GET['tab']:'hall_list';
	?>
<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div id="main-wrapper"  class=" class_list">  
<div class="panel panel-white">
					<div class="panel-body"> 
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_hall&tab=hall_list" class="nav-tab <?php echo $active_tab == 'hall_list' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Hall List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
       <a href="?page=smgt_hall&tab=addhall&action=edit&notice_id=<?php echo $_REQUEST['hall_id'];?>" class="nav-tab <?php echo $active_tab == 'addhall' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Hall', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_hall&tab=addhall" class="nav-tab <?php echo $active_tab == 'addhall' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Hall', 'school-mgt'); ?></a>  
        <?php } ?>
    </h2>
    <?php
	
	if($active_tab == 'hall_list')
	{	
	?>	
   		
    	
         <?php 
		 $retrieve_class = get_all_data($tablename);
		?>
         <div class="panel-body">
		 <script>
jQuery(document).ready(function() {
	var table =  jQuery('#hall_list').DataTable({
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
        <table id="hall_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>      
				<th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>          
                <th><?php _e('Hall Name','school-mgt');?></th>
                <th><?php _e('Hall Numeric Value','school-mgt');?></th>
                <th><?php _e('Capacity','school-mgt');?></th>
                <th><?php _e('Description','school-mgt');?></th>
                <th><?php _e('Action','school-mgt');?> </th>               
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
             	<th><?php _e('Hall Name','school-mgt');?></th>
                <th><?php _e('Hall Numeric Value','school-mgt');?></th>
                <th><?php _e('Capacity','school-mgt');?></th>
                <th><?php _e('Description','school-mgt');?></th>
                <th><?php _e('Action','school-mgt');?> </th>        
            </tr>
        </tfoot>
 
        <tbody>
          <?php 	
		 	foreach ($retrieve_class as $retrieved_data){ 		
		 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->hall_id;?>"></td>
                <td><?php echo $retrieved_data->hall_name;?></td>
                <td><?php echo $retrieved_data->number_of_hall;?></td>
                <td><?php echo $retrieved_data->hall_capacity;?></td>
                <td><?php echo $retrieved_data->description;?></td>          
               <td><a href="?page=smgt_hall&tab=addhall&action=edit&hall_id=<?php echo $retrieved_data->hall_id;?>" class="btn btn-info"><?php _e('Edit','school-mgt');?></a>
               <a href="?page=smgt_hall&tab=hall_list&action=delete&hall_id=<?php echo $retrieved_data->hall_id;?>" class="btn btn-danger" 
               onclick="return confirm('<?php _e('Are you sure you want to delete this record?','school-mgt');?>');"><?php _e('Delete','school-mgt');?></a></td>
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
	if($active_tab == 'addhall')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/hall/add-hall.php';
		
	 }
	 ?>
	 		</div>
	 	</div>
	 </div>
</div>
<?php ?>