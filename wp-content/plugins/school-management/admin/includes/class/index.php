		<?php 
	// This is Class at admin side!!!!!!!!! 
	if(isset($_POST['save_class']))
	{
		$created_date = date("Y-m-d H:i:s");
		$classdata=array('class_name'=>$_POST['class_name'],
						'class_num_name'=>$_POST['class_num_name'],
						'class_capacity'=>$_POST['class_capacity'],	
						'creater_id'=>get_current_user_id(),
						'created_date'=>$created_date
						
		);
		
		$tablename="smgt_class";
		
		if($_REQUEST['action']=='edit')
		{
			$classid=array('class_id'=>$_REQUEST['class_id']);
			$result=update_record($tablename,$classdata,$classid);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('record successfully Updated!','school-mgt');?></p>
					</div>
	  <?php }
		}
		else
		{
			$result=insert_record($tablename,$classdata);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('record successfully inserted!','school-mgt');?></p>
					</div>
	  <?php }
				
		}
		
	}
	$tablename="smgt_class";
	/*Delete selected Subject*/
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=delete_class($tablename,$id);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=delete_class($tablename,$_REQUEST['class_id']);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('record successfully delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}

	?>
	<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'classlist';
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
<div  id="main-wrapper" class="class_list">
	<div class="panel panel-white">
			<div class="panel-body">		
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_class&tab=classlist" class="nav-tab <?php echo $active_tab == 'classlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'. __('Class List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
         <a href="?page=smgt_class&tab=addclass&&action=edit&class_id=<?php echo $_REQUEST['class_id'];?>" class="nav-tab <?php echo $active_tab == 'addclass' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Class', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_class&tab=addclass" class="nav-tab <?php echo $active_tab == 'addclass' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Class', 'school-mgt'); ?></a>  
        <?php } ?>
    </h2>
    
    <?php
	
	if($active_tab == 'classlist')
	{	
	?>	
   		 
    	
         <?php 
		 	$retrieve_class = get_all_data($tablename);
			
			
			
		?><div class="panel-body">
		<script>
jQuery(document).ready(function() {
	var table =  jQuery('#class_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                  
	                  {"bSortable": false},
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
        <table id="class_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
                <th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>
                <th><?php _e('Class Name','school-mgt');?></th>
                <th><?php _e('Class Numeric Name','school-mgt');?></th>
                <!--<th><?php _e('Section','school-mgt');?></th>-->
                <th><?php _e('Capacity','school-mgt');?></th>
                <td><?php _e('Action','school-mgt');?></td>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
                <th><?php _e('Class Name','school-mgt');?></th>
                <th><?php _e('Class Numeric Name','school-mgt');?></th>
              <!--  <th><?php _e('Section','school-mgt');?></th>-->
                <th><?php _e('Capacity','school-mgt');?></th>
                <td><?php _e('Action','school-mgt');?></td>
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
		 	foreach ($retrieve_class as $retrieved_data){ 
			
		 ?>
            <tr>
				 <td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->class_id;?>"></td>
                <td><?php echo $retrieved_data->class_name;?></td>
                <td><?php echo $retrieved_data->class_num_name;?></td>
                <!--<td><?php echo $retrieved_data->class_section;?></td>-->
                <td><?php echo $retrieved_data->class_capacity;?></td>
               <td><a href="?page=smgt_class&tab=addclass&action=edit&class_id=<?php echo $retrieved_data->class_id;?>" class="btn btn-info"> <?php _e('Edit','school-mgt');?></a>
               <a href="?page=smgt_class&tab=classlist&action=delete&class_id=<?php echo $retrieved_data->class_id;?>" class="btn btn-danger" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php _e('Delete','school-mgt');?></a>
				<a class="btn btn-default" href="#" id="addremove" class_id="<?php echo $retrieved_data->class_id;?>" model="class_sec"><?php _e('View Or Add Section','school-mgt');?></a>
				
				</td>
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
       </div>
     <?php 
	 }
	if($active_tab == 'addclass')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/class/add-newclass.php';
		
	 }
	 ?>
	 
	 
	 </div>
</div>
</div>
<?php ?>