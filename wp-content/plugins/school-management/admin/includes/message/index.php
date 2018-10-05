<?php 
	// This is Class at admin side!!!!!!!!! 
if(isset($_POST['save_message']))
{

	$created_date = date("Y-m-d H:i:s");
	$subject = $_POST['subject'];
	$message_body = $_POST['message_body'];
	$created_date = date("Y-m-d H:i:s");
	$tablename="smgt_message";

	$role=$_POST['receiver'];
	
	if(isset($_REQUEST['class_id']))
	$class_id = $_REQUEST['class_id'];
	if($role == 'parent' || $role == 'student' || $role == 'teacher' || $role == 'supportstaff' )
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
		 			 $i = 0;
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
				 				$sender_id = $clickatell['sender_id'];//clickatell sender_id
				 				$baseurl ="http://api.clickatell.com";
				 					
				 				// auth call
				 				//echo $url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";
				 				//exit;	
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
				 			
				 		$reciever_id = $user_id;
						$message_data=array('sender'=>get_current_user_id(),
							'receiver'=>$user_id,
							'subject'=>$subject,
							'message_body'=>$message_body,
							'date'=>$created_date,
							'date'=>$post_id,
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
				
				
				$mail_id = array();
		 $i = 0;
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
				$sender_id = $clickatell['sender_id'];//clickatell apikey
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
						if(isset($result))
						{?>
							<div id="message" class="updated below-h2">
								<p><?php _e('Message Sent Successfully!','school-mgt');?></p>
							</div>
					<?php 
						}		
	?>
	<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'inbox';
	?>
<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div id="main-wrapper">
<div class="row mailbox-header">
                                <div class="col-md-2">
                                    <a class="btn btn-success btn-block" href="?page=smgt_message&tab=compose"><?php _e('Compose','school-mgt');?></a>
                                </div>
                                <div class="col-md-6">
                                    <h2>
                                    <?php
									if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
                                    echo esc_html( __( 'Inbox', 'school-mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'sentbox')
									echo esc_html( __( 'Sent Item', 'school-mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'compose')
										echo esc_html( __( 'Compose', 'school-mgt' ) );
									?>
								
                                    
                                    </h2>
                                </div>
                               
                            </div>
 <div class="col-md-2">
                            <ul class="list-unstyled mailbox-nav">
                                <li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
                                <a href="?page=smgt_message&tab=inbox"><i class="fa fa-inbox"></i> <?php _e('Inbox','school-mgt');?><span class="badge badge-success pull-right"><?php echo count(smgt_count_unread_message(get_current_user_id()));?></span></a></li>
                                <li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>><a href="?page=smgt_message&tab=sentbox"><i class="fa fa-sign-out"></i><?php _e('Sent','school-mgt');?></a></li>                                
                            </ul>
                        </div>
 <div class="col-md-10">
 <?php  
 	if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox')
 		require_once SMS_PLUGIN_DIR. '/admin/includes/message/sendbox.php';
 	if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
 		require_once SMS_PLUGIN_DIR. '/admin/includes/message/inbox.php';
 	if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'compose'))
 		require_once SMS_PLUGIN_DIR. '/admin/includes/message/composemail.php';
 	if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_message'))
 		require_once SMS_PLUGIN_DIR. '/admin/includes/message/view_message.php';
 	
 	?>
 </div>
</div><!-- Main-wrapper -->
</div><!-- Page-inner -->
<?php ?>