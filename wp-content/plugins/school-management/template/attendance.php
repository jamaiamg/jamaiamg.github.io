<?php 
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'daily_attendence';
$obj_attend=new Attendence_Manage();
$current_date = date("y-m-d");
$class_id =0;
if(isset($_REQUEST['save_attendence']))
{	 
		$class_id=$_POST['class_id'];
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
				}
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
				}
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

?>
<script>
$(document).ready(function() {
    $('#attendence_list').DataTable({
        responsive: true
    });
	$('#curr_date').datepicker({dateFormat: "yy-mm-dd"}); 
} );
</script>
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
       <li class="<?php if($active_tab=='daily_attendence'){?>active<?php }?>">
          <a href="?dashboard=user&page=attendance&tab=daily_attendence">
             <i class="fa fa-align-justify"></i> <?php _e('Attendance', 'school-mgt'); ?></a>
          </a>
      </li>
	  <li class="<?php if($active_tab=='sub_attendence'){?>active<?php }?>">
          <a href="?dashboard=user&page=attendance&tab=sub_attendence">
             <i class="fa fa-align-justify"></i> <?php _e('Subject Wise Attendance', 'school-mgt'); ?></a>
          </a>
      </li>
	 <!-- <li class="active">
          <a href="#attendance" role="tab" data-toggle="tab">
             <i class="fa fa-group"></i> <?php _e('Attendance', 'school-mgt'); ?></a>
          </a>
      </li>
	   <li class="active">
          <a href="#attendance" role="tab" data-toggle="tab">
             <i class="fa fa-group"></i> <?php _e('Attendance', 'school-mgt'); ?></a>
          </a>
      </li>-->
</ul>
<div class="tab-content">
<?php if($active_tab == 'daily_attendence')
	  {?>
                <div class="panel-body">
					 <form method="post">  
          <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
			
	  
                <div class="form-group col-md-3">
                   <label for="exampleInputEmail1"><?php _e('Current Date','school-mgt');?></label>
				   
					   <input id="curr_date" type="text" name="curr_date" value="<?php if(isset($_POST['curr_date'])) echo smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>"  class="form-control"/>
                        
              </div>
                <div class="form-group col-md-3">
				<label for="class_id"><?php _e('Select Class','school-mgt');?></label>
				<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>
                 
                    <select name="class_id"  id="class_list" class="form-control" style="line-height: 28px">
                        <option value=" "><?php _e('Select class Name','school-mgt');?></option>
                        <?php if(get_option('smgt_attendance_access')=='own_class')
						{	
							$class_data=get_allclass();
							foreach($class_data as $classdata)
							{  
							?>
                           <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
                     <?php }
					}
						else
						{
							$class_data=get_allclass();
							foreach($class_data as $classdata)
							{  
							?>
                           <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
                     <?php }
						} ?>
                          
                    </select>
                </div>
               	<div class="form-group col-md-3">
			<label for="class_id"><?php _e('Select Class Section','school-mgt');?></label>			
			<?php 
			$class_section="";
			if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
					<select name="class_section" class="form-control validate[required]" id="class_section">
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
                    
                    <input type="submit" value="<?php _e('Take/View  Attendance','school-mgt');?>" name="attendence" class="btn btn-success"/>
                </div>
          
          </form>
		  <div class="clearfix"></div>
         <?php 
         if(isset($_REQUEST['attendence']) || isset($_REQUEST['save_attendence']))
         {
         		if(isset($_REQUEST['class_id']) && $_REQUEST['class_id'] != " ")
                $class_id =$_REQUEST['class_id'];
         		else 
         			$class_id = 0;
         		if($class_id == 0)
         		{
         		?>
         		<div class="panel-heading">
         	<h4 class="panel-title"><?php _e('Please Select Class','school-mgt');?></h4>
         </div>
         		<?php          		}
         		else{
               
				if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != ""){
						
						$exlude_id = smgt_approve_student_list();
						$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
						 'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
					}
					else
					{ 
					  $exlude_id = smgt_approve_student_list();
					  $student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
					}
				?>
                
			
		   <form method="post" class="form-horizontal">  
          
         
          <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
          <input type="hidden" name="class_section" value="<?php echo $_REQUEST['class_section'];?>" />
          <input type="hidden" name="curr_date" value="<?php if(isset($_POST['curr_date'])) echo smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" />
        
         <div class="panel-heading">
         	<h4 class="panel-title"> <?php _e('Class','school-mgt')?> : <?php echo get_class_name($class_id);?> , 
         	<?php _e('Date','school-mgt')?> : <?php echo smgt_getdate_in_input_box($_POST['curr_date']);?></h4>
         </div>
        
          <div class="col-md-12">
          <div class="table-responsive">
        <table class="table">
            <tr><!--  
                <?php if($_REQUEST['curr_date'] == date("Y-m-d")){?> 
                   <th width="100px"><input type="checkbox" name="selectall" id="selectall"/></th>
                  <th width="100px"><?php _e('Status','school-mgt');?></th>
                  <?php }
                  else {
                  	?>
                  	<th width="100px"><?php _e('Status','school-mgt');?></th>
                  	<?php 
                  	
                  }  ?>-->
                  <th><?php _e('Srno','school-mgt');?></th>
				  <th><?php _e('Roll No.','school-mgt');?></th>
                <th><?php _e('Student','school-mgt');?></th>
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
				<td><input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control" value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>"></td><?php 
                
                echo '</tr>';
                $i++;}?>
                   
					</table>
					
					</div>
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
       
        </form>
		
        <?php }
         }
        ?>
       	
				
		</div>
	  <?php }
			if($active_tab == 'sub_attendence')
			{ ?>
		
<script type="text/javascript">

$(document).ready(function() {
	$('#subject_attendance').validationEngine();
});


</script>		
				<div class="panel-body"> 
        <form method="post" id="subject_attendance">  
          <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
          <div class="form-group col-md-2">
			<label class="control-label" for="curr_date"><?php _e('Date','school-mgt');?></label>
			
				<input id="curr_date" class="form-control" type="text" value="<?php if(isset($_POST['curr_date'])) echo smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" name="curr_date">
			
		</div>
		<?php if(get_option('smgt_attendance_access')=='own_subject')
			  {	?> 
			<div class="form-group col-md-3">
			<label for="class_id"><?php _e('Select Subject','school-mgt');?></label>			
			
                 <select name="sub_id"  id="subject_list"  class="form-control ">
                        <option value=" "><?php _e('Select Subject','school-mgt');?></option>
						<?php 
							if(isset($_POST['sub_id']))
									$sub_id=$_POST['sub_id'];
							else
								$sub_id=0;
							  $allsubjects = smgt_get_teacher_subjects(get_current_user_id());
						foreach($allsubjects as $subjectdata)
                          {?>
							<option value="<?php echo $subjectdata->subid;?>" <?php selected($subjectdata->subid,$sub_id); ?>><?php echo $subjectdata->sub_name;?></option>
                     <?php }
					
					  ?>
                    </select>
			
			</div> 
		    <?php    
			}
			else 
			{ ?>
		<div class="form-group col-md-2">
			<label for="class_id"><?php _e('Select Class','school-mgt');?><span class="require-field">*</span></label>			
			<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>
                 
                    <select name="class_id"  id="class_list"  class="form-control validate[required]">
                        <option value=" "><?php _e('Select class Name','school-mgt');?></option>
                        <?php 
                          foreach(get_allclass() as $classdata)
                          {  
                          ?>
                           <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
                     <?php }?>
                    </select>
			
		</div>
			<div class="form-group col-md-2">
			<label for="class_id"><?php _e('Select Section','school-mgt');?></label>			
			<?php 
			$class_section="";
			if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
					<select name="class_section" class="form-control" id="class_section">
                        	<option value=""><?php _e('Select Section','school-mgt');?></option>
						<?php if(isset($_REQUEST['class_section'])){
								$class_section=$_REQUEST['class_section']; 
								foreach(smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							} ?>		
	
                    </select>
		</div>
		<div class="form-group col-md-3">
			<label for="class_id"><?php _e('Select Subject','school-mgt');?><span class="require-field">*</span></label>			
			
                 <select name="sub_id"  id="subject_list"  class="form-control validate[required]">
                        <option value=" "><?php _e('Select Subject','school-mgt');?></option>
						<?php $sub_id=0;
							if(isset($_POST['sub_id'])){
									$sub_id=$_POST['sub_id'];
							  ?>
						<?php $allsubjects = smgt_get_subject_by_classid($_POST['class_id']);
                         foreach($allsubjects as $subjectdata)
                          {?>
							<option value="<?php echo $subjectdata->subid;?>" <?php selected($subjectdata->subid,$sub_id); ?>><?php echo $subjectdata->sub_name;?></option>
                     <?php }
						}
					  ?>
                    </select>
			
		</div> 
			<?php } ?>
		 <div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" value="<?php _e('Take/View  Attendance','school-mgt');?>" name="attendence"  class="btn btn-success"/>
    </div>
       
          </form>
		  </div>
           <div class="clearfix"> </div>
         <?php 
         if(isset($_REQUEST['attendence']) || isset($_REQUEST['save_sub_attendence']))
         {
         	if(isset($_REQUEST['class_id']) && $_REQUEST['class_id'] != " ")
                $class_id =$_REQUEST['class_id'];
         		else 
         			$class_id = 0;
         		if($class_id == 0)
         		{
         		?>
         		<div class="panel-heading">
         	<h4 class="panel-title"><?php _e('Please Select Class','school-mgt');?></h4>
         </div>
         		<?php          		}
         		else{
                
						if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != ""){
						
						$exlude_id = smgt_approve_student_list();
						$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
						 'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
						}
						else
						{ 
						  $exlude_id = smgt_approve_student_list();
						  $student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
						} 
				?>
               <div class="panel-body">  
            <form method="post"  class="form-horizontal">  
          
         
          <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
          <input type="hidden" name="sub_id" value="<?php echo $sub_id;?>" />
          <input type="hidden" name="class_section" value="<?php echo $_REQUEST['class_section'];?>" />
          <input type="hidden" name="curr_date" value="<?php if(isset($_POST['curr_date'])) echo smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" />
        
         <div class="panel-heading">
         	<h4 class="panel-title"> <?php _e('Class','school-mgt')?> : <?php echo get_class_name($class_id);?> , 
         	<?php _e('Date','school-mgt')?> : <?php echo smgt_getdate_in_input_box($_POST['curr_date']);?>,<?php _e('Subject')?> : <?php echo get_subject_byid($_POST['sub_id']); ?></h4>
         </div>
        
          <div class="col-md-12">
        <table class="table">
            <tr><!--  
                <?php if($_REQUEST['curr_date'] == date("Y-m-d")){?> 
                   <th width="100px"><input type="checkbox" name="selectall" id="selectall"/></th>
                  <th width="100px"><?php _e('Status','school-mgt');?></th>
                  <?php }
                  else {
                  	?>
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
                   
                    $check_attendance = $obj_attend->check_sub_attendence($user->ID,$class_id,$date,$_POST['sub_id']);
                 
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
				<td><input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control" value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>"></td><?php 
                
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
					<?php //if($_REQUEST['curr_date'] == date("Y-m-d")){ ?>       	
        	<input type="submit" value="<?php _e("Save  Attendance","school-mgt");?>" name="save_sub_attendence" class="btn btn-success" />
        	<?php //} ?>
        </div>
       
        </form>	</div>
        <?php }
         }
	} ?>
</div>	
</div>
<?php ?>