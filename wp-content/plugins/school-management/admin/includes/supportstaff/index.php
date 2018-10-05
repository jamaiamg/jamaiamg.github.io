<?php 
$role='supportstaff';
	if(isset($_POST['save_supportstaff']))
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
						'alternet_mobile_number'=>$_POST['alternet_mobile_number'],
						'working_hour'=>$_POST['working_hour'],
						'possition'=>$_POST['possition'],
						'smgt_user_avatar'=>$photo,
						
	);
	
	if($_REQUEST['action']=='edit')
	{
		
		$userdata['ID']=$_REQUEST['supportstaff_id'];
		$result=update_user($userdata,$usermetadata,$firstname,$lastname,$role);
		var_dump($result);
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
					<p><?php _e('record successfully inserted!','school-mgt');?></p>
				</div>
	  <?php }
		}
		else 
		{
			?>
											<div id="message" class="updated below-h2">
														<p><p><?php _e('Username Or Emailid All Ready Exist.','school-mgt');?></p></p>
													</div>
											<?php 
		}
	}
		
	
}

	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			
			$result=delete_usedata($_REQUEST['supportstaff_id']);
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
			$result=delete_usedata($id);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'supportstaff_list';
	
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
    	<a href="?page=smgt_supportstaff&tab=supportstaff_list" class="nav-tab <?php echo $active_tab == 'supportstaff_list' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Support Staff List', 'school-mgt'); ?></a>
    	
        <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
        <a href="?page=smgt_supportstaff&tab=addsupportstaff&&action=edit&supportstaff_id=<?php echo $_REQUEST['supportstaff_id'];?>" class="nav-tab <?php echo $active_tab == 'addsupportstaff' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Support Staff', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=smgt_supportstaff&tab=addsupportstaff" class="nav-tab <?php echo $active_tab == 'addsupportstaff' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New Support Staff', 'school-mgt'); ?></a>  
		<?php }?>
       
    </h2>
     <?php 
	//Report 1 
	if($active_tab == 'supportstaff_list')
	{ 
	
	?>	
    
    <form name="wcwm_report" action="" method="post">
    
        <div class="panel-body">
		<script>
jQuery(document).ready(function() {
	var table =  jQuery('#supportstaff_list').DataTable({
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
        <table id="supportstaff_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
			<th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th> 
			   <th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
                <th><?php _e( 'Name', 'school-mgt' ) ;?></th>			  
                <th> <?php _e( 'Email', 'school-mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'school-mgt' ) ;?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
				<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
              <th><?php _e( 'Name', 'school-mgt' ) ;?></th>			  
                <th> <?php _e( 'Email', 'school-mgt' ) ;?></th>
                <th><?php  _e( 'Action', 'school-mgt' ) ;?></th>
                
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		$teacherdata=get_usersdata('supportstaff');
		 if(!empty($teacherdata))
		 {
		 	foreach (get_usersdata('supportstaff') as $retrieved_data){ 
			
			
		 ?>
            <tr>
			<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->ID;?>"></td>
				<td class="user_image" width="50px"><?php $uid=$retrieved_data->ID;
							$umetadata=get_user_image($uid);
		 	if(empty($umetadata['meta_value']))
									{
										echo '<img src='.get_option( 'smgt_supportstaff_thumb' ).' height="50px" width="50px" class="img-circle" />';
									}
							else
							echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';
				?></td>
                <td class="name"><a href="?page=smgt_supportstaff&tab=addsupportstaff&action=edit&supportstaff_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a></td>
               
			
                <td class="email"><?php echo $retrieved_data->user_email;?></td>
               	<td class="action"> <a href="?page=smgt_supportstaff&tab=addsupportstaff&action=edit&supportstaff_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php _e('Edit', 'school-mgt' ) ;?></a>
                <a href="?page=smgt_supportstaff&tab=supportstaff_list&action=delete&supportstaff_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','school-mgt');?>');">
                <?php _e( 'Delete', 'school-mgt' ) ;?> </a>
                
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
       
</form>
     <?php 
	 }
	
	if($active_tab == 'addsupportstaff')
	 {
	require_once SMS_PLUGIN_DIR. '/admin/includes/supportstaff/add-staff.php';
	 }
	 ?>
</div>
			
		</div>
	</div>
</div>