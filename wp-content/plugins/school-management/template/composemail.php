<?php
if(isset($_POST['save_message']))
{
	if(isset($_POST['class_id']))
		$class_id =$_REQUEST['class_id'];
	
	$created_date = date("Y-m-d H:i:s");
	
	$subject = $_POST['subject'];
	$message_body = $_POST['message_body'];
	$created_date = date("Y-m-d H:i:s");
	$tablename="smgt_message";
	$role=$_POST['receiver'];
	if($role == 'parent' || $role == 'student' || $role == 'teacher' || $role == 'supportstaff' || $role == 'administrator')
	{
		if($_REQUEST['class_section']!="")
		{
			$userdata=smgt_get_user_notice($role,$_REQUEST['class_id'],$_REQUEST['class_section']);
		}
		else{
			$userdata=smgt_get_user_notice($role,$_REQUEST['class_id']);
		}
		
	
	if(!empty($userdata))
	{
		
		$smgt_sms_service_enable=isset($_REQUEST['smgt_sms_service_enable'])?$_REQUEST['smgt_sms_service_enable']:0;
		 if($smgt_sms_service_enable)
		 {
			
				
				
				 	$mail_id = array();
		 		foreach($userdata as $user)
					{
						
						if($role == 'parent' && $class_id != 'all')
						$mail_id[]=$user['ID'];
						else 
							$mail_id[]=$user->ID;
						
						$i++;
					}
				 
					$post_id = wp_insert_post( array(
							'post_status' => 'publish',
							'post_type' => 'message',
							'post_title' => $subject,
							'post_content' =>$message_body
						) );
				 	foreach($mail_id as $user_id)
				 	{
				 
				 		$user_info = get_userdata(1);
				 	
				 		$reciever_number = "+".smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
				 		
				 		$message_content = $_POST['sms_template'];
				 		$current_sms_service = get_option( 'smgt_sms_service');
				 		
				 			if($current_sms_service == 'clickatell')
				 			{
				 								 				
				 				$clickatell=get_option('smgt_clickatell_sms_service');
				 				$to = $reciever_number;
				 				$message = str_replace(" ","%20",$message_content);
				 				$username = $clickatell['username']; //clickatell username
				 				$password = $clickatell['password']; // clickatell password
				 				$api_key = $clickatell['api_key'];//clickatell apikey
				 				$baseurl ="http://api.clickatell.com";
				 					
				 				// auth call
				 				$url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";
				 					
				 				// do auth call
				 				$ret = file($url);
				 					
				 				// explode our response. return string is on first line of the data returned
				 				$sess = explode(":",$ret[0]);
				 				if ($sess[0] == "OK") {
				 						
				 					$sess_id = trim($sess[1]); // remove any whitespace
				 					$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message";
				 						
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
				 			
				 		$reciever_id = $user_id;
						$message_data=array('sender'=>get_current_user_id(),
							'receiver'=>$user_id,
							'subject'=>$subject,
							'message_body'=>$message_body,
							'date'=>$created_date,
							'post_id'=>$post_id,
							'status' =>0
						);
						insert_record($tablename,$message_data);
						$user_info = get_userdata($user_id);
						$to = $user_info->user_email;           
						wp_mail($to, $subject, $message_body); 
				 		
				 	}
						
				$result=add_post_meta($post_id, 'message_for',$role);
				$result=add_post_meta($post_id, 'smgt_class_id',$_REQUEST['class_id']);
				 	
				 	
				 
	}
	else
	{
		$i = 0;
			$mail_id = array();
	foreach($userdata as $user)
					{
						
						if($role == 'parent' && $class_id != 'all')
						$mail_id[]=$user['ID'];
						else 
							$mail_id[]=$user->ID;
						
						$i++;
					}
			
			$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => 'message',
					'post_title' => $subject,
					'post_content' =>$message_body
			
			) );
			foreach($mail_id as $user_id)
			{
				
				$reciever_id = $user_id;
				$message_data=array('sender'=>get_current_user_id(),
						'receiver'=>$user_id,
						'subject'=>$subject,
						'message_body'=>$message_body,
						'date'=>$created_date,
						'post_id'=>$post_id,
						'status' =>0
				);
				insert_record($tablename,$message_data);
			
			$user_info = get_userdata($user_id);
			$to = $user_info->user_email;           
			wp_mail($to, $subject, $message_body); 
			
			
			}
			
			
			
			$result=add_post_meta($post_id, 'message_for',$role);
			$result=add_post_meta($post_id, 'smgt_class_id',$_REQUEST['class_id']);	
	}	
			
	}
	}
	else 
	{

		$user_id = $_POST['receiver'];
		$user_info = get_userdata($user_id);
		$to = $user_info->user_email;           
		wp_mail($to, $subject, $message_body); 
		$smgt_sms_service_enable=isset($_REQUEST['smgt_sms_service_enable'])?$_REQUEST['smgt_sms_service_enable']:0;
		if($smgt_sms_service_enable)
		{
			$reciever_number = "+".smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
			$message_content = $_POST['sms_template'];
			$current_sms_service = get_option( 'smgt_sms_service');
		
			if($current_sms_service == 'clickatell')
			{
					
				$clickatell=get_option('smgt_clickatell_sms_service');
				$to = $reciever_number;
				$message = str_replace(" ","%20",$message_content);
				$username = $clickatell['username']; //clickatell username
				$password = $clickatell['password']; // clickatell password
				$api_key = $clickatell['api_key'];//clickatell apikey
				$baseurl ="http://api.clickatell.com";
					
				// auth call
				$url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";
					
				// do auth call
				$ret = file($url);
					
				// explode our response. return string is on first line of the data returned
				$sess = explode(":",$ret[0]);
				if ($sess[0] == "OK") {
		
					$sess_id = trim($sess[1]); // remove any whitespace
					$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message";
		
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
			$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => 'message',
					'post_title' => $subject,
					'post_content' =>$message_body
		
			) );
			$message_data=array('sender'=>get_current_user_id(),
					'receiver'=>$user_id,
					'subject'=>$subject,
					'message_body'=>$message_body,
					'date'=>$created_date,
					'post_id'=>$post_id,
					'status' =>0
			);
			insert_record($tablename,$message_data);
		
			
		
		
			$result=add_post_meta($post_id, 'message_for','user');
			$result=add_post_meta($post_id, 'message_smgt_user_id',$user_id);
		}
		else
		{
			$user_id =$_POST['receiver'];
				
				
			$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => 'message',
					'post_title' => $subject,
					'post_content' =>$message_body
						
			) );
					
			$message_data=array('sender'=>get_current_user_id(),
					'receiver'=>$user_id,
					'subject'=>$subject,
					'message_body'=>$message_body,
					'date'=>$created_date,
					'post_id'=>$post_id,
					'status' =>0
			);
			insert_record($tablename,$message_data);
				
			
				
			$result=add_post_meta($post_id, 'message_for','user');
			$result=add_post_meta($post_id, 'message_smgt_user_id',$user_id);
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
					_e('Message sent successfully','school-mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Message deleted successfully",'school-mgt');
					?></p>
					</div>
				<?php 
			
		}
}	
	

?>
<script type="text/javascript">

$(document).ready(function() {
	 $('#message_form').validationEngine();
} );
</script>
		<div class="mailbox-content">
		<h2>
        	 	<?php  $edit=0;
			 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						 echo esc_html( __( 'Edit Message', 'school-mgt') );
						 $edit=1;
						 $exam_data= get_exam_by_id($_REQUEST['exam_id']);
					}
					?>
        </h2>
        <?php
		if(isset($message))
			echo '<div id="message" class="updated below-h2"><p>'.$message.'</p></div>';
		?>
        <form name="class_form" action="" method="post" class="form-horizontal" id="message_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="to"><?php _e('Message To','school-mgt');?><span class="require-field">*</span></label>
                                            <div class="col-sm-8">
                                                <select name="receiver" class="form-control validate[required]" id="to" style="line-height: 28px">
							<option value="student"><?php _e('Student','school-mgt');?></option>	
							<option value="teacher"><?php _e('Teachers','school-mgt');?></option>	
							<option value="parent"><?php _e('Parents','school-mgt');?></option>	
							<option value="administrator"><?php _e('Admin','school-mgt');?></option>
							<option value="supportstaff"><?php _e('Support Staff','school-mgt');?></option>
							<?php echo smgt_get_all_user_in_message();?>
						</select>
                                            </div>
                                        </div>
         <div id="smgt_select_class">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="sms_template"><?php _e('Select Class','school-mgt');?></label>
			<div class="col-sm-8">
			
				 <select name="class_id"  id="class_list" class="form-control">
                	<option value="all"><?php _e('All','school-mgt');?></option>
                    <?php
					  foreach(get_allclass() as $classdata)
					  {  
					  ?>
					   <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
				 <?php }?>
                </select>
			</div>
		</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="class_name"><?php _e('Class Section','school-mgt');?></label>
			<div class="col-sm-8">
				<?php if(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
                        <select name="class_section" class="form-control" id="class_section">
                        	<option value=""><?php _e('Select Class Section','school-mgt');?></option>
                            <?php
							if($edit){
								foreach(smgt_get_class_sections($user_info->class_name) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							}?>
                        </select>
			</div>
		</div>
         <div class="form-group">
                                            <label class="col-sm-2 control-label" for="subject"><?php _e('Subject','school-mgt');?><span class="require-field">*</span></label>
                                            <div class="col-sm-8">
                                               <input id="subject" class="form-control validate[required]" type="text" name="subject" value="<?php if($edit){ echo $exam_data->exam_date;}?>">
                                            </div>
                                        </div>
          <div class="form-group">
                                            <label class="col-sm-2 control-label" for="subject"><?php _e('Message Comment','school-mgt');?></label>
                                            <div class="col-sm-8">
                                              <textarea name="message_body" id="message_body" class="form-control "><?php if($edit){ echo $exam_data->exam_comment;}?></textarea>
                                            </div>
                                        </div>
           <div class="form-group">
			<label class="col-sm-2 control-label " for="enable"><?php _e('Send SMS','school-mgt');?></label>
			<div class="col-sm-8">
				 <div class="checkbox">
				 	<label>
  						<input id="chk_sms_sent" type="checkbox"  value="1" name="smgt_sms_service_enable">
  					</label>
  				</div>
				 
			</div>
		</div>
		<div id="hmsg_message_sent" class="hmsg_message_none">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="sms_template"><?php _e('SMS Text','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea name="sms_template" class="form-control validate[required]" maxlength="160"></textarea>
				<label><?php _e('Max. 160 Character','school-mgt');?></label>
			</div>
		</div>
		</div>			
		   <div class="form-group">
		   <div class="col-sm-10 ">
                                          
                                            <div class="pull-right">
                                            <input type="submit" value="<?php if($edit){ _e('Save Message','school-mgt'); }else{ _e('Send Message','school-mgt');}?>" name="save_message" class="btn btn-success"/>
                                            </div>
                                            </div>
                                        </div>
         	
        
        </form>
        
        </div>
<?php

?>