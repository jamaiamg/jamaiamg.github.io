<?php 
	// This is Class at admin side!!!!!!!!! 
	$tablename="grade";
		if(isset($_POST['save_grade']))
	{
		
		$created_date = date("Y-m-d H:i:s");
		$gradedata=array('grade_name'=>$_POST['grade_name'],
						'grade_point'=>$_POST['grade_point'],
						'mark_from'=>$_POST['mark_from'],
						'mark_upto'=>$_POST['mark_upto'],
						'grade_comment'=>$_POST['grade_comment'],	
						'creater_id'=>get_current_user_id(),
						'created_date'=>$created_date
						
		);
		//table name without prefix
		$tablename="grade";
		
		if($_REQUEST['action']=='edit')
		{
			$grade_id=array('grade_id'=>$_REQUEST['grade_id']);
			$result=update_record($tablename,$gradedata,$grade_id);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Record successfully Updated!','school-mgt');?></p>
					</div>
	  <?php }
		}
		else
		{
			$result=insert_record($tablename,$gradedata);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Record successfully inserted!','school-mgt');?></p>
					</div>
	  <?php }
				
		}
		
	}

	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=delete_grade($tablename,$id);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
	$tablename="grade";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=delete_grade($tablename,$_REQUEST['grade_id']);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record successfully deleted!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
	?>
	<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'gradelist';
	?>


<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div  id="main-wrapper" class="grade_page">
	<div class="panel panel-white">
					<div class="panel-body">    
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_grade&tab=gradelist" class="nav-tab <?php echo $active_tab == 'gradelist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Grade List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
       <a href="?page=smgt_grade&tab=addgrade&action=edit&class_id=<?php echo $_REQUEST['grade_id'];?>" class="nav-tab <?php echo $active_tab == 'addgrade' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Grade', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_grade&tab=addgrade" class="nav-tab <?php echo $active_tab == 'addgrade' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. __('Add Grade', 'school-mgt'); ?></a>  
        <?php } ?>
    </h2>
    <?php
	
	if($active_tab == 'gradelist')
	{	
	?>	
   
         <?php 
		 	$retrieve_class = get_all_data($tablename);?>
        <div class="panel-body">
		<script>
jQuery(document).ready(function() {
	var table =  jQuery('#grade_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                  
	                  {"bSortable": false},
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
        <table id="grade_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>         
                <th><?php _e('Grade Name','school-mgt');?></th>
                <th><?php _e('Grade Point','school-mgt');?></th>
                <th><?php _e('Mark From','school-mgt');?></th>
                <th><?php _e('Mark Upto','school-mgt');?></th>
                <th><?php _e('Comment','school-mgt');?></th>
                <td><?php _e('Action','school-mgt');?></td>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
               <th><?php _e('Grade Name','school-mgt');?></th>
                <th><?php _e('Grade Point','school-mgt');?></th>
                <th><?php _e('Mark From','school-mgt');?></th>
                <th><?php _e('Mark Upto','school-mgt');?></th>
                <th><?php _e('Comment','school-mgt');?></th>	
                <td><?php _e('Action','school-mgt');?></td>
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
		 	foreach ($retrieve_class as $retrieved_data){ 
			
		 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->grade_id;?>"></td>
                <td><?php echo $retrieved_data->grade_name;?></td>
                <td><?php echo $retrieved_data->grade_point;?></td>
                <td><?php echo $retrieved_data->mark_from;?></td>
                <td><?php echo $retrieved_data->mark_upto;?></td>
                <td><?php echo $retrieved_data->grade_comment;?></td>
               <td><a href="?page=smgt_grade&tab=addgrade&action=edit&grade_id=<?php echo $retrieved_data->grade_id;?>" class="btn btn-info"><?php _e('Edit','school-mgt');?></a>
               <a href="?page=smgt_grade&tab=gradelist&action=delete&grade_id=<?php echo $retrieved_data->grade_id;?>" class="btn btn-danger" 
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
	if($active_tab == 'addgrade')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/grade/add-grade.php';
		
	 }
	 ?>
	 		</div>
	 	</div>
	 </div>
</div>
<?php ?>