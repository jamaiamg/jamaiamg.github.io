<?php 
	// This is Dashboard at admin side!!!!!!!!!
	$obj_mark = new Marks_Manage(); 
	
	//----------Add-updatecode---------------------
	$role='student';
	if(isset($_POST['active_user']))
	{		
		$active_user_id = $_REQUEST['act_user_id'];
		
		?>
		
<?php
		
		update_user_meta($active_user_id, 'roll_id', $_REQUEST['roll_id']);
		$class_name=get_user_meta($active_user_id,'class_name',true);
		$user_info = get_userdata($_POST['act_user_id']);
				$to = $user_info->user_email;           
				$subject = get_option('student_activation_title'); 
				$search=array('{{student_name}}','{{roll_number}}','{{user_name}} ','{{class_name}} ','{{email}}','{{school_name}}');
				$replace = array($user_info->display_name,$_REQUEST['roll_id'],$user_info->user_login,$class_name,$to,get_option( 'smgt_school_name' ));
				$messagedata = str_replace($search, $replace,get_option('student_activation_mailcontent'));	
				
				wp_mail($to, $subject, $messagedata); 
		if(get_user_meta($active_user_id, 'hash', true))
			delete_user_meta($active_user_id, 'hash');
		
	wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=7');
		
	}
	if(isset($_POST['exportstudentin_csv']))
	{
		
		if($_REQUEST['class_name'] != "")
		{
			$student_list = get_users(array('meta_key' => 'class_name', 'meta_value' => $_REQUEST['class_name'], 'role'=>'student'));
		}
		else
		{
			$student_list = get_users(array('role'=>'student'));
		}
		
		if(!empty($student_list))
		{
			$header = array();			
			$header[] = 'Username';
			$header[] = 'Email';
			$header[] = 'Roll No';
			$header[] = 'Class Name';
			$header[] = 'First Name';
			$header[] = 'Middle Name';
			$header[] = 'Last Name';			
			$header[] = 'Gender';
			$header[] = 'Birth Date';
			$header[] = 'Address';
			$header[] = 'City Name';
			$header[] = 'State Name';
			$header[] = 'Zip Code';
			$header[] = 'Mobile Number';
			$header[] = 'Alternate Mobile Number';			
			$header[] = 'Phone Number';	
			$filename='Reports/export_student.csv';
			$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
			fputcsv($fh, $header);
			foreach($student_list as $retrive_data)
			{
				$row = array();
				$user_info = get_userdata($retrive_data->ID);
				
				$row[] = $user_info->user_login;
				$row[] = $user_info->user_email;
				$row[] =  get_user_meta($retrive_data->ID, 'roll_id',true);				
				$class_id=  get_user_meta($retrive_data->ID, 'class_name',true);	
				$classname=get_class_name($class_id);	
				$row[] = $classname;	
				$row[] =  get_user_meta($retrive_data->ID, 'first_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'middle_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'last_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'gender',true);
				$row[] =  get_user_meta($retrive_data->ID, 'birth_date',true);
				$row[] =  get_user_meta($retrive_data->ID, 'address',true);
				$row[] =  get_user_meta($retrive_data->ID, 'city',true);
				$row[] =  get_user_meta($retrive_data->ID, 'state',true);
				$row[] =  get_user_meta($retrive_data->ID, 'zip_code',true);
				$row[] =  get_user_meta($retrive_data->ID, 'mobile_number',true);
				$row[] =  get_user_meta($retrive_data->ID, 'alternet_mobile_number',true);
				$row[] =  get_user_meta($retrive_data->ID, 'phone',true);
				
				fputcsv($fh, $row);
				
			}
			fclose($fh);
	
		//download csv file.
		ob_clean();
		$file=SMS_PLUGIN_DIR.'/admin/Reports/export_student.csv';//file location
		
		$mime = 'text/plain';
		header('Content-Type:application/force-download');
		header('Pragma: public');       // required
		header('Expires: 0');           // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Content-Transfer-Encoding: binary');
		//header('Content-Length: '.filesize($file_name));      // provide file size
		header('Connection: close');
		readfile($file);		
	exit;	
			
		}
		else
		{
			echo "<div style=' background: none repeat scroll 0 0 red;
					border: 1px solid;
					color: white;
					float: left;
					font-size: 17px;
					margin-top: 10px;
					padding: 10px;
					width: 98%;'>Records not found.</div>";
		}
		
	}
	if(isset($_POST['save_student']))
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
	$usermetadata=array('roll_id'=>$_POST['roll_id'],
						'middle_name'=>$_POST['middle_name'],
						'gender'=>$_POST['gender'],
						'birth_date'=>$_POST['birth_date'],
						'address'=>$_POST['address'],
						'city'=>$_POST['city_name'],
						'state'=>$_POST['state_name'],
						'zip_code'=>$_POST['zip_code'],
						'class_name'=>$_POST['class_name'],
						'class_section'=>$_POST['class_section'],
						'phone'=>$_POST['phone'],
						'mobile_number'=>$_POST['mobile_number'],
						'alternet_mobile_number'=>$_POST['alternet_mobile_number'],
						'smgt_user_avatar'=>$photo,
						
	);
	$userbyroll_no=get_users(
			array('meta_query'=>
					array('relation' => 'AND',
							array('key'=>'class_name','value'=>$_POST['class_name']),
							array('key'=>'roll_id','value'=>$_POST['roll_id'])
					),
					'role'=>'student'));
					
	$is_rollno = count($userbyroll_no);
	if($_REQUEST['action']=='edit')
	{		
		$userdata['ID']=$_REQUEST['student_id'];			
		$result=update_user($userdata,$usermetadata,$firstname,$lastname,$role);
		wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=2'); 
	  						
	}
	else
	{
		if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
			
			if($is_rollno)
			{
					wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=3'); 
			}
			else {
						
		 $result=add_newuser($userdata,$usermetadata,$firstname,$lastname,$role);
			if($result)
			{ 
	  		wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=1'); 	  
	  	 }
		}
		}
		else {
			wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=4');
		}
	 
	}
		
}
	
	// -----------Delete Code--------
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
		
			$childs=get_user_meta($_REQUEST['student_id'], 'parent_id', true);
			
			
			if(!empty($childs))
			{
				foreach($childs as $key=>$childvalue)
				{
					
					$parents=get_user_meta($childvalue, 'child',true);
					
					
					if(!empty($parents))
					{
					if(($key = array_search($_REQUEST['student_id'], $parents)) !== false) {
						unset($parents[$key]);						
						update_user_meta( $childvalue,'child', $parents );
						
					}
					
					}
				
				}
			}
		
			$result=delete_usedata($_REQUEST['student_id']);
			if($result)
			{
		wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=5');
			}
		}
		if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
		{
			$childs=get_user_meta($id, 'parent_id', true);
			
			
			if(!empty($childs))
			{
				foreach($childs as $key=>$childvalue)
				{
					
					$parents=get_user_meta($childvalue, 'child',true);
					
					
					if(!empty($parents))
					{
					if(($key = array_search($id, $parents)) !== false) {
						unset($parents[$key]);						
						update_user_meta( $childvalue,'child', $parents );
						
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
		if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf')
		{
			$sudent_id = $_REQUEST['student'];
			downlosd_smgt_result_pdf($sudent_id);
		}
		
		
		if(isset($_REQUEST['upload_csv_file'])){
		
			if(isset($_FILES['csv_file'])){
				
				$errors= array();
				$file_name = $_FILES['csv_file']['name'];
				$file_size =$_FILES['csv_file']['size'];
				$file_tmp =$_FILES['csv_file']['tmp_name'];
				$file_type=$_FILES['csv_file']['type'];
				//$file_ext=strtolower(end(explode('.',$_FILES['csv_file']['name'])));
				$value = explode(".", $_FILES['csv_file']['name']);
				$file_ext = strtolower(array_pop($value));
				$extensions = array("csv");
				$upload_dir = wp_upload_dir();
				if(in_array($file_ext,$extensions )=== false){
					$errors[]="this file not allowed, please choose a CSV file.";
				}
				if($file_size > 2097152){
					$errors[]='File size limit 2 MB';
				}
				
				if(empty($errors)==true){
		
					$rows = array_map('str_getcsv', file($file_tmp));		
						
					$header = array_map('strtolower',array_shift($rows));
					
					$csv = array();
					foreach ($rows as $row) {
						$csv = array_combine($header, $row);
						
						$username = $csv['username'];
						$email = $csv['email'];
						$user_id = 0;
						$password = $csv['password'];
						$problematic_row = false;
						
						if( username_exists($username) ){ // if user exists, we take his ID by login
							$user_object = get_user_by( "login", $username );
							$user_id = $user_object->ID;
						
							if( !empty($password) )
								wp_set_password( $password, $user_id );
						}
						elseif( email_exists( $email ) ){ // if the email is registered, we take the user from this
							$user_object = get_user_by( "email", $email );
							$user_id = $user_object->ID;					
							$problematic_row = true;
						
							if( !empty($password) )
								wp_set_password( $password, $user_id );
						}
						else{
							if( empty($password) ) // if user not exist and password is empty but the column is set, it will be generated
								$password = wp_generate_password();
						
							$user_id = wp_create_user($username, $password, $email);
						}

						if( is_wp_error($user_id) ){ // in case the user is generating errors after this checks
							echo '<script>alert("Problems with user: ' . $username . ', we are going to skip");</script>';
							continue;
						}

						if(!( in_array("administrator", smgt_get_roles($user_id), FALSE) || is_multisite() && is_super_admin( $user_id ) ))
							wp_update_user(array ('ID' => $user_id, 'role' => 'student')) ;
						update_user_meta( $user_id, "active", true );
						
						update_user_meta( $user_id, "class_name", $_POST['class_name']);
						
						
						
						if(isset($csv['class_section']))
							update_user_meta( $user_id, "class_section", $csv['class_section'] );
						if(isset($csv['roll_no']))
							update_user_meta( $user_id, "roll_id", $csv['roll_no'] );
						if(isset($csv['first_name']))
							update_user_meta( $user_id, "first_name", $csv['first_name'] );
						if(isset($csv['last_name']))
							update_user_meta( $user_id, "last_name", $csv['last_name'] );
						if(isset($csv['middle_name']))
							update_user_meta( $user_id, "middle_name", $csv['middle_name'] );
						if(isset($csv['gender']))
							update_user_meta( $user_id, "gender", $csv['gender'] );
						if(isset($csv['birth_date']))
							update_user_meta( $user_id, "birth_date", $csv['birth_date'] );
						if(isset($csv['address']))
						update_user_meta( $user_id, "address", $csv['address'] );
						if(isset($csv['city_name']))
						update_user_meta( $user_id, "city", $csv['city_name'] );
						if(isset($csv['state_name']))
							update_user_meta( $user_id, "state", $csv['state_name'] );						
						if(isset($csv['zip_code']))
							update_user_meta( $user_id, "zip_code", $csv['zip_code'] );
						if(isset($csv['mobile_number']))
							update_user_meta( $user_id, "mobile_number", $csv['mobile_number'] );
						if(isset($csv['alternet_mobile_number']))
							update_user_meta( $user_id, "alternet_mobile_number", $csv['alternet_mobile_number'] );						
						if(isset($csv['phone']))
							update_user_meta( $user_id, "phone", $csv['phone'] );
						
						
						$success = 1;
		
					}
				}else{
					foreach($errors as &$error) echo $error;
				}
			if(isset($success))
			{
			
			wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=6');
			} 
			}
		}
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="result">
     </div>
      <div class="view-parent"></div>
    </div>
    </div> 
    
</div>
<?php 
if(isset($_REQUEST['attendance']) && $_REQUEST['attendance'] == 1)
{
?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('.sdate').datepicker({dateFormat: "yy-mm-dd"}); 
	$('.edate').datepicker({dateFormat: "yy-mm-dd"}); 

 
} );
</script>


<div class="page-inner" style="min-height:1631px !important">
	<div class="page-title"> 
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div id="main-wrapper">
		<div class="row">
			<div class="panel panel-white">
				<div class="panel-body">
				<h2 class="nav-tab-wrapper">
			    	<a href="?page=smgt_student&attendance=1" class="nav-tab nav-tab-active">
					<?php echo '<span class="dashicons dashicons-menu"></span>'.__('View Attendance', 'school-mgt'); ?></a>
				</h2>
				<form name="wcwm_report" action="" method="post">
<input type="hidden" name="attendance" value=1> 
<input type="hidden" name="user_id" value=<?php echo $_REQUEST['student_id'];?>>       
	<div class="form-group col-md-3">
    	<label for="exam_id"><?php _e('Strat Date','school-mgt');?></label>
       
					
            	<input type="text"  class="form-control sdate" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>">
            	
    </div>
    <div class="form-group col-md-3">
    	<label for="exam_id"><?php _e('End Date','school-mgt');?></label>
			<input type="text"  class="form-control edate" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>">
            	
    </div>
    <div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" name="view_attendance" Value="<?php _e('Go','school-mgt');?>"  class="btn btn-info"/>
    </div>	
</form>
<div class="clearfix"></div>
<?php if(isset($_REQUEST['view_attendance']))
{
	$start_date = $_REQUEST['sdate'];
	$end_date = $_REQUEST['edate'];
	$user_id = $_REQUEST['user_id'];
	$attendance = smgt_view_student_attendance($start_date,$end_date,$user_id);
	
	$curremt_date =$start_date;
	?>
	<table class="table col-md-12">
	<tr>
	<th width="200px"><?php _e('Date','school-mgt');?></th>
	<th><?php _e('Day','school-mgt');?></th>
	<th><?php _e('Attendance','school-mgt');?></th>
	<th><?php _e('Comment','school-mgt');?></th>
	</tr>
	<?php 
	while ($end_date >= $curremt_date)
	{
		echo '<tr>';
		echo '<td>';
		echo $curremt_date;
		echo '</td>';
		
		$attendance_status = smgt_get_attendence($user_id,$curremt_date);
		echo '<td>';
		echo date("D", strtotime($curremt_date));
		echo '</td>';
		
		if(!empty($attendance_status))
		{
			echo '<td>';
			echo smgt_get_attendence($user_id,$curremt_date);
			echo '</td>';
		}
		else 
		{
			echo '<td>';
			echo __('Absent','school-mgt');
			echo '</td>';
		}
		echo '<td>';
		echo smgt_get_attendence_comment($user_id,$curremt_date);
		echo '</td>';
		echo '</tr>';
		$curremt_date = strtotime("+1 day", strtotime($curremt_date));
		$curremt_date = date("Y-m-d", $curremt_date);
	}
?>
</table>

<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 

}
else 
{
?>
	<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'studentlist';
	?>

<div class="page-inner" style="min-height:1631px !important">
	<div class="page-title"> 
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div id="main-wrapper">
	<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = __('Record successfully inserted!','school-mgt');
			break;
		case '2':
			$message_string = __('Record Successfully Updated!','school-mgt');
			break;
		case '3':
			$message_string = __('Roll No All Ready Exist!','school-mgt');
			break;
		case '4':
			$message_string = __('Username Or Emailid All Ready Exist','school-mgt');
			break;
		case '5':
			$message_string = __('Record Successfully Delete!','school-mgt');
			break;
		case '6':
			$message_string = __('Student CSV Successfully Uploaded.','school-mgt');
			break;
		case '7':
			$message_string = __('Student Activated Auccessfully!','school-mgt');
			break;
		
	}
	if($message)
	{
		?>
		<div id="message" class="updated below-h2">
						<p><?php echo $message_string;?></p>
					</div>
		<?php
	}
?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
					<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_student&tab=studentlist" class="nav-tab <?php echo $active_tab == 'studentlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Student List', 'school-mgt'); ?></a>
    	 <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{ ?>
        <a href="?page=smgt_student&tab=addstudent&&action=edit&student_id=<?php echo $_REQUEST['student_id'];?>" class="nav-tab <?php echo $active_tab == 'addstudent' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Student', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
        
        <a href="?page=smgt_student&tab=addstudent" class="nav-tab <?php echo $active_tab == 'addstudent' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New student', 'school-mgt'); ?></a>  
        <?php }?>
        
        <a href="?page=smgt_student&tab=uploadstudent" class="nav-tab <?php echo $active_tab == 'uploadstudent' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Upload Student CSV', 'school-mgt'); ?></a>
       
         <a href="?page=smgt_student&tab=exportstudent" class="nav-tab <?php echo $active_tab == 'exportstudent' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Export Student', 'school-mgt'); ?></a>
		
        
    </h2>
     <?php 
	 if($active_tab == 'studentlist')
	 {
		
	//Report 1 
	?>
   <div class="panel-body"> 
   
        <form method="post">  
   <div class="form-group col-md-3">
			<label for="class_id"><?php _e('Select Class','school-mgt');?></label>			
			<?php 
			$class_id="";
			if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>
                 
                    <select name="class_id"  id="class_list"  class="form-control ">
                        <option value=" "><?php _e('Select class Name','school-mgt');?></option>
                        <?php 
                          foreach(get_allclass() as $classdata)
                          {  
                          ?>
                           <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
                     <?php }?>
                    </select>
		</div>
		 <div class="form-group col-md-3">
			<label for="class_id"><?php _e('Select Class Section','school-mgt');?></label>			
			<?php 
			$class_section="";
			?>
					<select name="class_section" class="form-control validate[required]" id="class_section">
                        	<option value=""><?php _e('Select Class Section','school-mgt');?></option>
						<?php if(isset($_REQUEST['class_section'])){
								$class_section=$_REQUEST['class_section']; 
								foreach(smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							}?>		
	
                    </select>
		</div>
		 <div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" value="<?php _e('Go','school-mgt');?>" name="filter_class"  class="btn btn-info"/>
    </div>
       
          </form>
		  </div>
		 <?php  if(isset($_REQUEST['filter_class']) )
				 {
					
					if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != ""){
						$class_id =$_REQUEST['class_id'];
						$class_section =$_REQUEST['class_section'];
						 $studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$class_section,'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student'));	
					}
					if(isset($_REQUEST['class_id']) && $_REQUEST['class_section'] == ""){
						
						$class_id =$_REQUEST['class_id'];
						 $studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));	
						}	
				}	
				 else 
				{
					$exlude_id = smgt_approve_student_list();
					$studentdata =get_users(array('role'=>'student'));
				}
         		?>
            
   
    
        <div class="panel-body">
		<script>
jQuery(document).ready(function() {
	var table =  jQuery('#students_list').DataTable({
        responsive: true,
		"order": [[ 2, "asc" ]],
		"aoColumns":[	                  
	                  {"bSortable": false},
	                  {"bSortable": false},
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
        <table id="students_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th> 
				<th><?php echo _e( 'Photo', 'school-mgt' ) ;?></th>
                <th><?php echo _e( 'Student Name', 'school-mgt' ) ;?></th>
                 <th> <?php echo _e( 'Roll No.', 'school-mgt' ) ;?></th>
				 <th> <?php echo _e( 'Class', 'school-mgt' ) ;?></th>
				 <th> <?php echo _e( 'Section', 'school-mgt' ) ;?></th>
                <th> <?php echo _e( 'Student Email', 'school-mgt' ) ;?></th>
				<th> <?php echo _e( 'Status', 'school-mgt' ) ;?></th>
                <th><?php echo _e( 'Action', 'school-mgt' ) ;?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
				 <th><?php echo _e( 'Photo', 'school-mgt' ) ;?></th>
               <th><?php echo _e( 'Student Name', 'school-mgt' ) ;?></th>
                <th> <?php echo _e( 'Roll No.', 'school-mgt' ) ;?></th>
			    <th> <?php echo _e( 'Class', 'school-mgt' ) ;?></th>
				<th> <?php echo _e( 'Section', 'school-mgt' ) ;?></th>
                <th> <?php echo _e( 'Student Email', 'school-mgt' ) ;?></th>
				<th> <?php echo _e( 'Status', 'school-mgt' ) ;?></th>
               <th><?php echo _e( 'Action', 'school-mgt' ) ;?></th>
                
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		 
			//$studentdata=get_usersdata('student');
		 	if(!empty($studentdata))
			{
					foreach ($studentdata as $retrieved_data){ 
					
					
				 ?>
					<tr>
					<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->ID;?>"></td>
						 <td class="user_image"><?php $uid=$retrieved_data->ID;
									$umetadata=get_user_image($uid);
									if(empty($umetadata['meta_value']))
									{
										echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle" />';
									}
									else
									echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle" />';
						?></td>
						<td class="name"><a href="?page=smgt_student&tab=addstudent&action=edit&student_id=<?php echo $retrieved_data->ID;?>">
						<?php echo $retrieved_data->display_name;?></a></td>
						<td class="roll_no">
						<?php 
						if(get_user_meta($retrieved_data->ID, 'roll_id', true))
						echo get_user_meta($retrieved_data->ID, 'roll_id',true);
						?>
						</td>
						<td class="name"><?php $class_id=get_user_meta($retrieved_data->ID, 'class_name',true);
											echo $classname=get_class_name($class_id);
						?></td>
						<td class="name"><?php $section_name=get_user_meta($retrieved_data->ID, 'class_section',true);
										if($section_name!=""){
											echo smgt_get_section_name($section_name); 
										}
										else
										{
											_e('No Section','school-mgt');;
										}
				?></td>
						<td class="email"><?php echo $retrieved_data->user_email;?></td>
					   <td> <?php 
					   if( get_user_meta($retrieved_data->ID, 'hash', true))
		{
			echo '<span class="btn btn-default active-user" idtest="'.$retrieved_data->ID.'"> ';
		_e( 'Active', 'school-mgt' ) ;
		echo " </span>"; }
		else
			_e( 'Approved', 'school-mgt' );?></td>
						<td class="action"> <a href="?page=smgt_student&tab=addstudent&action=edit&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"><?php _e('Edit','school-mgt');?></a>  
											<a href="?page=smgt_student&tab=studentlist&action=delete&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
											onclick="return confirm('Are you sure you want to delete this record?');"><?php _e('Delete','school-mgt');?></a> 
											<a href="?page=smgt_student&tab=studentlist&action=result&student_id=<?php echo $retrieved_data->ID;?>" class="show-popup btn btn-default" 
											idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-bar-chart"></i> <?php _e('View Result', 'school-mgt');?></a>
											<a href="?page=smgt_student&tab=studentlist&action=showparent&student_id=<?php echo $retrieved_data->ID;?>" class="show-parent btn btn-default" 
											idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-user"></i> <?php _e('View Parent', 'school-mgt');?></a>
											<a href="?page=smgt_student&student_id=<?php echo $retrieved_data->ID;?>&attendance=1" class="btn btn-default" 
											idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php _e('View Attendance','school-mgt');?></a>
											
						</td>
					   
					</tr>
					<?php } 
					
			} ?>
     
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

	
	if($active_tab == 'addstudent')
	 {
	require_once SMS_PLUGIN_DIR. '/admin/includes/student/student.php';
	 }
	 if($active_tab == 'uploadstudent')
	 {
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/student/uploadstudent.php';
	 }
	if($active_tab == 'exportstudent')
	 {
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/student/exportstudent.php';
	 }
	 ?>
					
				</div>
			</div>
		</div>
	</div>
</div>
<?php }?>