<?php 
	// This is Class at admin side!!!!!!!!! 
	$obj_attend=new Attendence_Manage();
	
	$class_id =0;

	$current_date = date("y-m-d");
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'attendence';
	if(isset($_REQUEST['save_attendence']))
	{
		
		
		$class_id=$_POST['class_id'];
		$parent_list = smgt_get_user_notice('parent',$class_id);
		
		$attend_by=get_current_user_id();
		//$exlude_id = smgt_approve_student_list();
					if(isset($_POST['class_section']) && $_POST['class_section'] !=0){
							
							$exlude_id = smgt_approve_student_list();
							$students = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
							 'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
						}
						else
						{ 
						  $exlude_id = smgt_approve_student_list();
						  $students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
						}
			
		
		//$students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
		
		foreach($students as $stud)
		{
			
			if(isset($_POST['attendanace_'.$stud->ID]))
			{
				
				if(isset($_POST['smgt_sms_service_enable']))
				{
					
					$current_sms_service = get_option( 'smgt_sms_service');
					if($_POST['attendanace_'.$stud->ID] == 'Absent')
					{
						
						$parent_list = smgt_get_student_parent_id($stud->ID);
						if(!empty($parent_list))
						{
							
							foreach ($parent_list as $user_id)
							{
								$reciever_number = "+".smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
								
								$message_content = "Your Child ".get_user_name_byid($stud->ID)." is absent today.";
								if($current_sms_service == 'clickatell')
								{
									
									$clickatell=get_option('smgt_clickatell_sms_service');
									$to = $reciever_number;
									$message = str_replace(" ","%20",$message_content);
									$username = $clickatell['username']; //clickatell username
									$password = $clickatell['password']; // clickatell password
									$api_key = $clickatell['api_key'];//clickatell apikey
									$sender_id = $clickatell['sender_id'];//clickatell sender_id
									$baseurl ="http://api.clickatell.com";
										
									// auth call
									$url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";
									//echo  "in clickatell";
									
									// do auth call
									$ret = file($url);
										
									// explode our response. return string is on first line of the data returned
									$sess = explode(":",$ret[0]);
									if ($sess[0] == "OK") {
											
										$sess_id = trim($sess[1]); // remove any whitespace
										$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message&from=$sender_id";
											
										// do sendmsg call
										$ret = file($url);
										$send = explode(":",$ret[0]);
										//print_r($send);
										/*
										 if ($send[0] == "ID") {
										 echo "successnmessage ID: ". $send[1];
										 } else {
										 echo "send message failed";
										 }
										*/
									}
								
								
								
								}
								if($current_sms_service == 'twillo')
								{
									//Twilio lib
									require_once SMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
									$twilio=get_option( 'smgt_twillo_sms_service');
									$account_sid = $twilio['account_sid']; //Twilio SID
									$auth_token = $twilio['auth_token']; // Twilio token
									$from_number = $twilio['from_number'];//My number
									$receiver = $reciever_number; //Receiver Number
									
									$message = $message_content; // Message Text
									//twilio object
									$client = new Services_Twilio($account_sid, $auth_token);
									$message_sent = $client->account->messages->sendMessage(
											$from_number, // From a valid Twilio number
											$receiver, // Text this number
											$message
									);
								
								}
							}
						}
					}
				}
				
				//print_r($_POST['attendanace_'.$stud->ID]);
				$savedata = $obj_attend->insert_student_attendance($_POST['curr_date'],$class_id,$stud->ID,$attend_by,$_POST['attendanace_'.$stud->ID],$_POST['attendanace_comment_'.$stud->ID]);
				
			}
					
		}
		
		?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Attendance successfully saved!','school-mgt');?></p>
					</div>
	  <?php 
	 
	}
	if(isset($_REQUEST['save_sub_attendence']))
	{
		$class_id=$_POST['class_id'];
		$parent_list = smgt_get_user_notice('parent',$class_id);
		
		$attend_by=get_current_user_id();
		
		$exlude_id = smgt_approve_student_list();
		$students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
		foreach($students as $stud)
		{
			
			if(isset($_POST['attendanace_'.$stud->ID]))
			{
				
				if(isset($_POST['smgt_sms_service_enable']))
				{
					$current_sms_service = get_option( 'smgt_sms_service');
					if($_POST['attendanace_'.$stud->ID] == 'Absent')
					{
						$parent_list = smgt_get_student_parent_id($stud->ID);
						if(!empty($parent_list))
						{
							foreach ($parent_list as $user_id)
							{
								$reciever_number = "+".smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
								
								$message_content = "Your Child ".get_user_name_byid($stud->ID)." is absent today.";
								if($current_sms_service == 'clickatell')
								{
										
									$clickatell=get_option('smgt_clickatell_sms_service');
									$to = $reciever_number;
									$message = str_replace(" ","%20",$message_content);
									$username = $clickatell['username']; //clickatell username
									$password = $clickatell['password']; // clickatell password
									$api_key = $clickatell['api_key'];//clickatell apikey
									$sender_id = $clickatell['sender_id'];//clickatell sender_id
									$baseurl ="http://api.clickatell.com";
										
									// auth call
									$url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";
										
									// do auth call
									$ret = file($url);
										
									// explode our response. return string is on first line of the data returned
									$sess = explode(":",$ret[0]);
									if ($sess[0] == "OK") {
											
										$sess_id = trim($sess[1]); // remove any whitespace
										$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message&from=$sender_id";
											
										// do sendmsg call
										$ret = file($url);
										$send = explode(":",$ret[0]);
										//print_r($send);
										/*
										 if ($send[0] == "ID") {
										 echo "successnmessage ID: ". $send[1];
										 } else {
										 echo "send message failed";
										 }
										*/
									}
								
								
								
								}
								if($current_sms_service == 'twillo')
								{
									//Twilio lib
									require_once SMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
									$twilio=get_option( 'smgt_twillo_sms_service');
									$account_sid = $twilio['account_sid']; //Twilio SID
									$auth_token = $twilio['auth_token']; // Twilio token
									$from_number = $twilio['from_number'];//My number
									$receiver = $reciever_number; //Receiver Number
									$message = $message_content; // Message Text
									//twilio object
									$client = new Services_Twilio($account_sid, $auth_token);
									$message_sent = $client->account->messages->sendMessage(
											$from_number, // From a valid Twilio number
											$receiver, // Text this number
											$message
									);
								
								}
							}
						}
					}
				}
				//var_dump($_POST);
				//exit;
				//print_r($_POST['attendanace_'.$stud->ID]);
				$savedata = $obj_attend->insert_subject_wise_attendance($_POST['curr_date'],$class_id,$stud->ID,$attend_by,$_POST['attendanace_'.$stud->ID],$_POST['sub_id'],$_POST['attendanace_comment_'.$stud->ID]);
			}
					
		}
		
		?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Attendance successfully saved!','school-mgt');?></p>
					</div>
	  <?php 
	 
	}
	//Teacher attendence 
		if(isset($_REQUEST['save_teach_attendence']))
		{
			 
			
			$attend_by=get_current_user_id();
			 //$result=$obj_attend->save_teacher_attendence($_POST['tcurr_date'],$_POST['attendence'],$attend_by,$_POST['status']);
			
		$teacher = get_users(array('role' => 'teacher'));
		foreach($teacher as $stud)
		{
			if(isset($_POST['attendanace_'.$stud->ID]))
			{
				$savedata = $obj_attend->insert_teacher_attendance($_POST['tcurr_date'],$stud->ID,$attend_by,$_POST['attendanace_'.$stud->ID],$_POST['attendanace_comment_'.$stud->ID]);
			}
					
		}
		if($savedata)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Attendance successfully saved!','school-mgt');?></p>
					</div>
	  <?php }
			 
		}


?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#curr_date').datepicker({dateFormat: "yy-mm-dd"}); 
} );
</script>
<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div id="main-wrapper" class=" attendance_list"> 
 	<div class="panel panel-white">
					<div class="panel-body">    
	<h2 class="nav-tab-wrapper">
	
    	<a href="?page=smgt_attendence&tab=attendence" class="nav-tab <?php echo $active_tab == 'attendence' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-groups"></span>'.__('Attendance', 'school-mgt'); ?></a>
        <a href="?page=smgt_attendence&tab=teacher_attendence" class="nav-tab <?php echo $active_tab == 'teacher_attendence' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-businessman"></span>'.__('Teacher  Attendance', 'school-mgt'); ?></a>
        <a href="?page=smgt_attendence&tab=subject_attendence" class="nav-tab <?php echo $active_tab == 'subject_attendence' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-groups"></span>'.__('Subject Wise Attendance', 'school-mgt'); ?></a>
    </h2>
    <?php
	if($active_tab == 'attendence')
	{ ?>	 
		<div class="panel-body"> 
        <form method="post" >  
          <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
          <div class="form-group col-md-3">
			<label class="control-label" for="curr_date"><?php _e('Date','school-mgt');?></label>
			
				<input id="curr_date" class="form-control" type="text" value="<?php if(isset($_POST['curr_date'])) echo $_POST['curr_date']; else echo  date("Y-m-d");?>" name="curr_date">
			
		</div>
		<div class="form-group col-md-3">
			<label for="class_id"><?php _e('Select Class','school-mgt');?></label>			
			<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>
                 
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
			if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
					<select name="class_section" class="form-control" id="class_section">
                        	<option value=""><?php _e('Select Class Section','school-mgt');?></option>
						<?php if(isset($_REQUEST['class_section'])){
								$class_section=$_REQUEST['class_section']; 
								foreach(smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							} ?>		
	
                    </select>
		</div>
		 <div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" value="<?php _e('Take/View  Attendance','school-mgt');?>" name="attendence"  class="btn btn-info"/>
    </div>
       
          </form>
		  </div>
           <div class="clearfix"> </div>
         <?php 
         if(isset($_REQUEST['attendence']) || isset($_REQUEST['save_attendence']))
         {
			 //var_dump($_REQUEST);
			
         	if(isset($_REQUEST['class_id']) && $_REQUEST['class_id'] != "")
                $class_id =$_REQUEST['class_id'];
         		else 
         			$class_id = 0;
         		if($class_id == 0)
         		{ ?>
					<div class="panel-heading">
						<h4 class="panel-title"><?php _e('Please Select Class','school-mgt');?></h4>
					</div>
         		<?php }
         		else
				{
					$class_section=0;
					if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] !=0){
						$class_section=$_REQUEST['class_section'];
						$exlude_id = smgt_approve_student_list();
						$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
						 'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
					}
					else
					{ 
					  $exlude_id = smgt_approve_student_list();
					  $student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
					} ?>
               <div class="panel-body">  
            <form method="post"  class="form-horizontal">  
          
         
          <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
          <input type="hidden" name="class_section" value="<?php echo $class_section;?>" />
          <input type="hidden" name="curr_date" value="<?php if(isset($_POST['curr_date'])) echo smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" />
        
         <div class="panel-heading">
         	<h4 class="panel-title"> <?php _e('Class','school-mgt')?> : <?php echo get_class_name($class_id);?> , 
         	<?php _e('Date','school-mgt')?> : <?php echo smgt_getdate_in_input_box($_POST['curr_date']);?></h4>
         </div>
        
          <div class="col-md-12">
        <table class="table">
            <tr><!--  
                <?php if($_REQUEST['curr_date'] == date("Y-m-d")){ ?> 
                   <th width="100px"><input type="checkbox" name="selectall" id="selectall"/></th>
                  <th width="100px"><?php _e('Status','school-mgt');?></th>
                  <?php }
                  else { ?>
                  	<th width="100px"><?php _e('Status','school-mgt');?></th>
                  	<?php 
                   }  ?>-->
                  <th><?php _e('Srno','school-mgt');?></th>
				  <th><?php _e('Roll No.','school-mgt');?></th>
                <th><?php _e('Student Name','school-mgt');?></th>
                 <th><?php _e('Attendance','school-mgt');?></th>
                 <th><?php _e('Comment','school-mgt');?></th>
            </tr>
            <?php
            $date = $_POST['curr_date'];
            $i = 1;

             foreach ( $student as $user ) {
            	$date = $_POST['curr_date'];
                   
                    $check_attendance = $obj_attend->check_attendence($user->ID,$class_id,$date);
                    $attendanc_status = "Present";
                    if(!empty($check_attendance))
                    {
                    	$attendanc_status = $check_attendance->status;
                    	
                    }
                   
                echo '<tr>';
              
                echo '<td>'.$i.'</td>';
				echo '<td><span>' .get_user_meta($user->ID, 'roll_id',true). '</span></td>';
                echo '<td><span>' .$user->first_name.' '.$user->last_name. '</span></td>';
                ?>
                <td><label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Present" <?php checked( $attendanc_status, 'Present' );?>>
                <?php _e('Present','school-mgt');?></label>
				<label class="radio-inline"> <input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Absent" <?php checked( $attendanc_status, 'Absent' );?>>
				<?php _e('Absent','school-mgt');?></label>
				<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Late" <?php checked( $attendanc_status, 'Late' );?>>
				<?php _e('Late','school-mgt');?></label></td>
				<td><input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control" 
				value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>"></td>
				<?php 
                
                echo '</tr>';
                $i++;}?>
                   
					</table>
					<div class="form-group">
			<label class="col-sm-4 control-label " for="enable"><?php _e('If student absent then Send  SMS to his/her parents','school-mgt');?></label>
			<div class="col-sm-2">
				 <div class="checkbox">
				 	<label>
  						<input id="chk_sms_sent1" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="smgt_sms_service_enable">
  					</label>
  				</div>
				 
			</div>
		</div>
		
					</div>
					<div class="col-sm-12"> 
					<?php //if($_REQUEST['curr_date'] == date("Y-m-d")){?>       	
        	<input type="submit" value="<?php _e('Save  Attendance','school-mgt');?>" name="save_attendence" class="btn btn-success" />
        	<?php //} ?>
        </div>
       
        </form>	</div>
        <?php }
         }
        ?>
        
       
    
<?php 
	}
	if($active_tab == 'teacher_attendence')
	{ 
		require_once SMS_PLUGIN_DIR. '/admin/includes/attendence/teacher-attendence.php';
	}
	if($active_tab == 'subject_attendence')
	{ 
		require_once SMS_PLUGIN_DIR. '/admin/includes/attendence/subject-attendence.php';
	}
?>
	</div>
	</div>
</div>
</div>

<?php ?>