<?php 
	// Add Noptice!!!!!!!!! 
	
		if(isset($_POST['save_notice']))
		{		
			if(isset($_POST['class_id']))
			$class_id =$_REQUEST['class_id'];
			
			if($_REQUEST['action']=='edit')
			{
				$args = array(
						  'ID'           => $_REQUEST['notice_id'],
						  'post_title'   => $_REQUEST['notice_title'],
						  'post_content' =>  $_REQUEST['notice_content'],
						 
					  	);
			$result1=wp_update_post( $args );
			$result2=update_post_meta($_REQUEST['notice_id'], 'notice_for', $_REQUEST['notice_for']);
			$result3=update_post_meta($_REQUEST['notice_id'], 'start_date',$_REQUEST['start_date']);
			$result4=update_post_meta($_REQUEST['notice_id'], 'end_date',$_REQUEST['end_date']);
			if(isset($_POST['class_id']))
			$result5=update_post_meta($_REQUEST['notice_id'], 'smgt_class_id',$_REQUEST['class_id']);
			if(isset($_POST['class_section']))
			$result6=update_post_meta($_REQUEST['notice_id'], 'smgt_section_id',$_REQUEST['class_section']);
			$role=$_POST['notice_for'];
			$smgt_sms_service_enable=0;
			$current_sms_service_active =get_option( 'smgt_sms_service');
			 if(isset($_POST['smgt_sms_service_enable']))
					$smgt_sms_service_enable = $_POST['smgt_sms_service_enable'];
		
			if($smgt_sms_service_enable)
			{
			
			
				$userdata=smgt_get_user_notice($role,$_REQUEST['class_id'],$_REQUEST['class_section']);
				//var_dump($userdata);
				if(!empty($userdata))
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
					
						
					foreach($mail_id as $user_id)
					{
							
						$user_info = get_userdata(1);
			
						$reciever_number = "+".smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
						
						//$user_name = get_user_meta($user_id, 'first_name',true)." ".get_user_meta($user_id, 'last_name',true);
						//$user_email = $user_info->user_email;
						//$notice_title =$_REQUEST['notice_title'];
						//$notice_content =$_REQUEST['notice_content'];
						//$notice_for =$_POST['notice_for'];
						//$notice_start_date = $_POST['start_date'];
						//$notice_end_date = $_POST['end_date'];
						//$search = array("[SMS_USER_NAME]", "[SMS_USER_EMAIL]","[SMS_EVENT_TITLE]","[SMS_EVENT_FOR]","[SMS_EVENT_CONTENT]","[SMS_EVENT_START_DATE] ","[SMS_EVENT_END_DATE]");
						//$replace   = array($user_name, $user_email,$notice_title,$notice_for,$notice_content,$notice_start_date,$notice_end_date);
						//$message =get_option( 'smgt_sms_template')." =>>";
						$message_content = $_POST['sms_template'];
						$current_sms_service = get_option( 'smgt_sms_service');
						//$current_sms_service = "dgdggd";
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
			
							
							
					}
			
			
				}
			}
				if($result1 || $result2 || $result3 || $result4 || isset($result5))
				{?>
					<div id="message" class="updated below-h2">
							<p><?php _e('Record Successfully Updated!','school-mgt');?></p>
						</div>
		  <?php }
			}
		else
		{
			
			$post_id = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => 'notice',
						'post_title' => $_REQUEST['notice_title'],
						'post_content' => $_REQUEST['notice_content']
						
					) );
			
			if(!empty($_POST['notice_for']))
			{
				 delete_post_meta($post_id, 'notice_for');
				 $result=add_post_meta($post_id, 'notice_for',$_POST['notice_for']);
				 $result=add_post_meta($post_id, 'start_date',$_POST['start_date']);
				 $result=add_post_meta($post_id, 'end_date',$_POST['end_date']);
				 if(isset($_POST['class_id']))
				 $result=add_post_meta($post_id, 'smgt_class_id',$_POST['class_id']);
				if(isset($_POST['class_section']))
				$result6=update_post_meta($_REQUEST['notice_id'], 'smgt_section_id',$_REQUEST['class_section']);
				 $role=$_POST['notice_for'];
				 $smgt_sms_service_enable=0;
				 $current_sms_service_active =get_option( 'smgt_sms_service');
				 if(isset($_POST['smgt_sms_service_enable']))
					$smgt_sms_service_enable = $_POST['smgt_sms_service_enable'];
				 if($smgt_sms_service_enable)
				 {
				 	
				
				 $userdata=smgt_get_user_notice($role,$_POST['class_id']);
				
				 if(!empty($userdata))
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
				 
				 
				 	foreach($mail_id as $user_id)
				 	{
				 
				 		$user_info = get_userdata(1);
				 	
				 		$reciever_number = "+".smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
				 		
				 		//$user_name = get_user_meta($user_id, 'first_name',true)." ".get_user_meta($user_id, 'last_name',true);
				 		//$user_email = $user_info->user_email;
				 		//$notice_title =$_REQUEST['notice_title'];
				 		//$notice_content =$_REQUEST['notice_content'];
				 		//$notice_for =$_POST['notice_for'];
				 		//$notice_start_date = $_POST['start_date'];
				 		//$notice_end_date = $_POST['end_date'];
				 		//$search = array("[SMS_USER_NAME]", "[SMS_USER_EMAIL]","[SMS_EVENT_TITLE]","[SMS_EVENT_FOR]","[SMS_EVENT_CONTENT]","[SMS_EVENT_START_DATE] ","[SMS_EVENT_END_DATE]");
				 		//$replace   = array($user_name, $user_email,$notice_title,$notice_for,$notice_content,$notice_start_date,$notice_end_date);
				 		//$message =get_option( 'smgt_sms_template')." =>>";
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
				 			
				 		
				 		
				 	}
				 	
				 	
				 }
				 }
				
				 if($result)
				{?>
					<div id="message" class="updated below-h2">
						<p><?php _e('Record Successfully Insert!','school-mgt');?></p>
					</div>
			<?php 
				}
				
			}
			
		}
		
		
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=wp_delete_post($id);
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
		$result=wp_delete_post($_REQUEST['notice_id']);
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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'noticelist';
	?>
<!-- View Popup Code -->	
<div class="popup-bg">
    <div class="overlay-content">
   
    	<div class="notice_content"></div>    
    
    </div> 
    
</div>	
<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div  id="main-wrapper" class="notice_page">
	<div class="panel panel-white">
					<div class="panel-body">    

	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_notice&tab=noticelist" class="nav-tab <?php echo $active_tab == 'noticelist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Notice List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
       <a href="?page=smgt_notice&tab=addnotice&action=edit&notice_id=<?php echo $_REQUEST['notice_id'];?>" class="nav-tab <?php echo $active_tab == 'addnotice' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Notice', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_notice&tab=addnotice" class="nav-tab <?php echo $active_tab == 'addnotice' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Notice', 'school-mgt'); ?></a>  
        <?php } ?>
    </h2>
    <?php
	
	
	if($active_tab == 'noticelist')
	{	
	?>	
  
    	
        <div class="panel-body">
		<script>
jQuery(document).ready(function() {
	var table =  jQuery('#notice_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                  
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
        <table id="notice_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>       
				<th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>         
                <th width="190px"><?php _e('Notice Title','school-mgt');?></th>
                <th><?php _e('Notice Comment','school-mgt');?></th>
                <th><?php _e('Notice Start Date','school-mgt');?></th>
				<th><?php _e('Notice End Date','school-mgt');?></th>
                <th><?php _e('Notice For','school-mgt');?></th>
                 <th><?php _e('Class','school-mgt');?></th>
                <th width="185px"><?php _e('Action','school-mgt');?></th>               
            </tr>
        </thead>	
 
        <tfoot>
            <tr>
				<th></th>
            	<th><?php _e('Notice Title','school-mgt');?></th>
                <th><?php _e('Notice Comment','school-mgt');?></th>
               <th><?php _e('Notice Start Date','school-mgt');?></th>
				<th><?php _e('Notice End Date','school-mgt');?></th>
                <th><?php _e('Notice For','school-mgt');?></th>
                 <th><?php _e('Class','school-mgt');?></th>
                <th><?php _e('Action','school-mgt');?></th>           
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
		  $args['post_type'] = 'notice';
		  $args['posts_per_page'] = -1;
		  $args['post_status'] = 'public';
		  $q = new WP_Query();
	$retrieve_class = $q->query( $args );
	$format =get_option('date_format') ;
		 	foreach ($retrieve_class as $retrieved_data){ 
			
		 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->ID;?>"></td>
                <td><?php echo $retrieved_data->post_title;?></td>
                <td><?php 
					$strlength= strlen($retrieved_data->post_content);
					if($strlength > 60)
						echo substr($retrieved_data->post_content, 0,60).'...';
					else
						echo $retrieved_data->post_content;
				
				?></td>
                <td><?php echo smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'start_date',true));?></td> 
				<td><?php echo smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'end_date',true));?></td> 				
                <td><?php echo get_post_meta( $retrieved_data->ID, 'notice_for',true);?></td>   
                 <td>
                 <?php 
                 if(get_post_meta( $retrieved_data->ID, 'smgt_class_id',true) !="" && get_post_meta( $retrieved_data->ID, 'smgt_class_id',true) =="all")
				 {
					 _e('All','school-mgt');
				 }
				 elseif(get_post_meta( $retrieved_data->ID, 'smgt_class_id',true) !=""){
                 echo get_class_name(get_post_meta( $retrieved_data->ID, 'smgt_class_id',true));}?></td>              
               <td display="inline">
                <a href="#" class="btn btn-primary view-notice" id="<?php echo $retrieved_data->ID;?>"> <?php _e('View','school-mgt');?></a>
               <a href="?page=smgt_notice&tab=addnotice&action=edit&notice_id=<?php echo $retrieved_data->ID;?>"class="btn btn-info"><?php _e('Edit','school-mgt');?></a>
               <a href="?page=smgt_notice&tab=noticelist&action=delete&notice_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
               onclick="return confirm('<?php _e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php _e('Delete','school-mgt');?></a>
               
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
       
     <?php 
	 }
	if($active_tab == 'addnotice')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/notice/add-notice.php';
		
	 }
	 ?>
	 	</div>
	 	</div>
	 </div>
</div>
<?php ?>