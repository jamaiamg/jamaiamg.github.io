<?php 
	// This is Dashboard at admin side!!!!!!!!! 
	
	$role='parent';
	if(isset($_POST['save_parent']))
	{
			$firstname=$_POST['first_name'];
			$lastname=$_POST['last_name'];
			$userdata = array(
			'user_login'=>$_POST['username'],			
			'user_nicename'=>NULL,
			'user_email'=>$_POST['email'],
			'user_url'=>NULL,
			'display_name'=>$firstname." ".$lastname,
			);
			
			if($_POST['password'] != "")
				$userdata['user_pass']=$_POST['password'];
		
	     
			if(isset($_POST['smgt_user_avatar']) && $_POST['smgt_user_avatar'] != "")
			{
				$photo=$_POST['smgt_user_avatar'];
			}
			else
			{
				$photo="";
			}
			$usermetadata=array('middle_name'=>$_POST['middle_name'],
								'gender'=>$_POST['gender'],
								'birth_date'=>$_POST['birth_date'],
								'address'=>$_POST['address'],
								'city'=>$_POST['city_name'],
								'state'=>$_POST['state_name'],
								'zip_code'=>$_POST['zip_code'],
								'phone'=>$_POST['phone'],
								'mobile_number'=>$_POST['mobile_number'],
								'relation'=>$_POST['relation'],
								'smgt_user_avatar'=>$photo,
								
			);
	
			if($_REQUEST['action']=='edit')
			{
				
				 $userdata['ID']=$_REQUEST['parent_id'];
				 $result=update_user($userdata,$usermetadata,$firstname,$lastname,$role);
					if($result)
					{?>
						<div id="message" class="updated below-h2">
								<p><?php _e('record successfully Updated!','school-mgt');?></p>
							</div>
			 <?php }
			}
			else
			{
				if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
				$result=add_newuser($userdata,$usermetadata,$firstname,$lastname,$role);
				if($result)
				{?>
					<div id="message" class="updated below-h2">
						<p><?php _e('Record successfully inserted!','school-mgt');?></p>
					</div>
		  <?php } 
				}
				else {
					?>
								<div id="message" class="updated below-h2">
											<p><?php _e('Username Or Emailid All Ready Exist.','school-mgt');?></p>
										</div>
								<?php 
				}
		  
			}
			
	}
	$addparent=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'addparent')
	{
		if(isset($_REQUEST['student_id']))
		{
			
			$student=get_userdata($_REQUEST['student_id']);
			$addparent=1;
		}
	}
	
$active_tab = isset($_GET['tab'])?$_GET['tab']:'parentlist';
	?>

<?php 
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			$childs=get_user_meta($_REQUEST['parent_id'], 'child', true);
			if(!empty($childs))
			{
				foreach($childs as $childvalue)
				{
					$parents=get_user_meta($childvalue, 'parent_id', true);
					if(!empty($parents))
					{
					if(($key = array_search($_REQUEST['parent_id'], $parents)) !== false) {
						
						unset($parents[$key]);
						
							update_user_meta( $childvalue,'parent_id', $parents );
						
					}
					}
				}
			}
			$result=delete_usedata($_REQUEST['parent_id']);	
			if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('record successfully delete!','school-mgt');?></p>
				</div>
		<?php 
			}
		}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
		{
			$childs=get_user_meta($id, 'child', true);
			if(!empty($childs))
			{
				foreach($childs as $childvalue)
				{
					$parents=get_user_meta($childvalue, 'parent_id', true);
					if(!empty($parents))
					{
					if(($key = array_search($id, $parents)) !== false) {
						
						unset($parents[$key]);
						
							update_user_meta( $childvalue,'parent_id', $parents );
						
					}
					}
				}
			}
			$result=delete_usedata($id);	
		}
			
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
		
?>
<div class="page-inner" style="min-height:1631px !important">
  	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_parent&tab=parentlist" class="nav-tab <?php echo $active_tab == 'parentlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Parent List', 'school-mgt'); ?></a>
    	
        <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
        <a href="?page=smgt_parent&tab=addparent&&action=edit&parent_id=<?php echo $_REQUEST['parent_id'];?>" class="nav-tab <?php echo $active_tab == 'addparent' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Parent', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=smgt_parent&tab=addparent" class="nav-tab <?php echo $active_tab == 'addparent' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add New Parent', 'school-mgt'); ?></a>  
		<?php }?>
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'parentlist')
	{ 
	?>	
  
	<div class="panel-body">
	<script>
jQuery(document).ready(function() {
	var table =  jQuery('#parent_list').DataTable({
        responsive: true,
		"order": [[ 2, "asc" ]],
		"aoColumns":[	                  
	                  {"bSortable": false},
	                  {"bSortable": false},	                  	                
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
			  <form name="frm-example" action="" method="post">
        <table id="parent_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th> 
				<th width="75px"><?php echo _e('Photo', 'school-mgt' ) ;?></th>
                <th><?php echo _e( 'Parent Name', 'school-mgt' ) ;?></th>
                <th> <?php echo _e( 'Parent Email', 'school-mgt' ) ;?></th>
                <th><?php echo _e( 'Action', 'school-mgt' ) ;?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
				<th><?php echo _e( 'Photo', 'school-mgt' ) ;?></th>
               <th><?php echo _e( 'Parent Name', 'school-mgt' ) ;?></th>
                <th> <?php echo _e( 'Parent Email', 'school-mgt' ) ;?></th>
                <th><?php echo _e( 'Action', 'school-mgt' ) ;?></th>
                
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
			$parentdata=get_usersdata('parent');
			if($parentdata)
			{
				foreach ($parentdata as $retrieved_data){ ?>	
				<tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->ID;?>"></td>
					<td class="user_image "><?php $uid=$retrieved_data->ID;
								$umetadata=get_user_image($uid);
								if(empty($umetadata['meta_value']))
									{
										echo '<img src='.get_option( 'smgt_parent_thumb' ).' height="50px" width="50px" class="img-circle" />';
									}
								else
								echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';
					?></td>
					<td class="name"><a href="?page=smgt_parent&tab=addparent&action=edit&parent_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a></td>
					<td class="email"><?php echo $retrieved_data->user_email;?></td>
					
					<td class="action"> <a href="?page=smgt_parent&tab=addparent&action=edit&parent_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php echo _e( ' Edit', 'school-mgt' ) ;?></a>
										<a href="?page=smgt_parent&tab=parentlist&action=delete&parent_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?');"><?php echo _e( ' Delete', 'school-mgt' ) ;?> </a>
					</td>
				   
				</tr>
				<?php } 
				
		}?>
     
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
	
	if($active_tab == 'addparent')
	 {
	require_once SMS_PLUGIN_DIR. '/admin/includes/parent/add-newparent.php';
	 }
	 ?>				
	 			</div><!-- Panel white -->
	 		</div><!-- col-md-12 -->
	 	</div><!-- Row -->
	 </div><!-- #mainwrapper -->
</div><!-- page-inner -->
<?php ?>