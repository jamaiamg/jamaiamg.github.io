<?php 
	
	$school_obj = new School_Management ( get_current_user_id () );
	$user = wp_get_current_user ();
	
	$user_data =get_userdata( $user->ID);
	require_once ABSPATH . 'wp-includes/class-phpass.php';
	$wp_hasher = new PasswordHash( 8, true );
	
	if(isset($_POST['save_change']))
	{
		$referrer = $_SERVER['HTTP_REFERER'];
		
		$success=0;
		if($wp_hasher->CheckPassword($_REQUEST['current_pass'],$user_data->user_pass))
		{
			
			if(isset($_REQUEST['new_pass'])==$_REQUEST['conform_pass'])
			{
				 wp_set_password( $_REQUEST['new_pass'], $user->ID);
					$success=1;
			}
			else
			{
				wp_redirect($referrer.'&sucess=2');
			}
			
		}
		else{
			
			wp_redirect($referrer.'&sucess=3');
		}
		if($success==1)
		{
			 wp_cache_delete($user->ID,'users');
			wp_cache_delete($user_data->user_login,'userlogins');
			wp_logout();
			if(wp_signon(array('user_login'=>$user_data->user_login,'user_password'=>$_REQUEST['new_pass']),false)):
				$referrer = $_SERVER['HTTP_REFERER'];
				
				wp_redirect($referrer.'&sucess=1');
			endif;
			ob_start();
		}else{
    wp_set_auth_cookie($user->ID, true);
		}
		
	
	}
?>
<?php 
	$coverimage=get_option( 'smgt_school_background_image' );
	if($coverimage!="")
	{?>

<style>
.profile-cover{
	background: url("<?php echo get_option( 'smgt_school_background_image' );?>") repeat scroll 0 0 / cover rgba(0, 0, 0, 0);
}
<?php }?>



</style>
<div> 
	<div class="profile-cover">
			<div class="row">
				
						<div class="col-md-3 profile-image">
									<div class="profile-image-container">
									<?php 
										$umetadata=get_user_image($user->ID);
													if(empty($umetadata['meta_value'])){
														echo '<img src='.get_option( 'smgt_student_thumb' ).' height="150px" width="150px" class="img-circle" />';
													}
													else
														echo '<img src='.$umetadata['meta_value'].' height="150px" width="150px" class="img-circle" />';
									?>
									</div>
						</div>
						
				</div>
			</div>
				
	<?php 
		 if(($school_obj->role)=='teacher')
		 {
			$teacher_id=$user->ID;
		 }
	?>
	<div Id="main-wrapper"> 
		<div class="row">
			<div class="col-md-3 user-profile">
				<h3 class="text-center">
					<?php 
						echo $user_data->display_name;
					?>
				</h3>
				<p class="text-center">
				<?php 
				if(isset($teacher_id)){
					echo '<strong>'.__('Teach Subject','school-mgt').' : </strong>'.get_subject_name_by_teacher($teacher_id);
					$user_info=get_userdata($user->ID);
				}
				if(($school_obj->role)=='student'){
					$user_info=get_userdata($user->ID);
					echo "Class : ".get_class_name($user_info->class_name);
					
				} ?></p>
				
				
				<hr>
				<ul class="list-unstyled text-center">
				<li>
				<p><i class="fa fa-map-marker m-r-xs"></i>
					<a href="#"><?php echo $user_data->address.",".$user_data->city;?></a></p>
				</li>	
				<li><i class="fa fa-envelope m-r-xs"></i>
							<a href="#"><?php echo 	$user_data->user_email;?></a></p>
				</p></li>
				</ul>
			</div>
			<?php 
			 if(($school_obj->role)!='teacher')
			 {
				 if(($school_obj->role)=='student')
				 {
					 $user_meta =get_user_meta($user->ID, 'parent_id', true); 
					 $title="Parent";
					 
				 }
				 else if(($school_obj->role)=='parent')
				 {
					 $user_meta =get_user_meta($user->ID, 'child', true); 
					 $title="child";
				 }
				 else
				 {
					  $user_meta = NULL;
					  
				 }
				 
				 
				?>
				<div class="col-md-6 m-t-lg">
				<div class="panel panel-white">
				<div class="panel-heading">
										<div class="panel-title"><?php _e('Account Settings ','school-mgt');?>	</div>
									</div>
									<div class="panel-body">
						<form class="form-horizontal" action="#" method="post">
								<div class="form-group">
									<label  class="control-label clo-md-6 col-sm-6 col-xs-12"></label>
									<div class="col-xs-10">	
										<p>
										<h4 class="bg-danger"><?php 
										if(isset($_REQUEST['sucess']))
										{ 
											if($_REQUEST['sucess']==1)
											{
												echo _e('Password successfully change','smgt_school'); 
											}
											
											
										}?></h4>
									</p>
									</div>
							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Name','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input type="Name" class="form-control " id="name" placeholder="Full Name" value="<?php echo $user->display_name; ?>" readonly>
									
								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Username','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input type="username" class="form-control " id="name" placeholder="Full Name" value="<?php echo $user->user_login; ?>" readonly>
									
								</div>

							</div>
														<div class="form-group">

								<label for="inputPassword" class="control-label clo-md-2 col-sm-2 col-xs-12 "><?php _e('Current Password','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input type="password" class="form-control" id="inputPassword" placeholder="Password" name="current_pass">

								</div>

							</div>
					<div class="form-group">

								<label for="inputPassword" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('New Password','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input type="password" class="validate[required] form-control" id="inputPassword" placeholder="New Password" name="new_pass">

								</div>

							</div><div class="form-group">

								<label for="inputPassword" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Conform Password','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input type="password" class="validate[required] form-control" id="inputPassword" placeholder="Conform Password" name="conform_pass">

								</div>

							</div>
							

							<div class="form-group">

								<div class="col-xs-offset-2 clo-md-8 col-sm-8 col-xs-12">

									<button type="submit" class="btn btn-success" name="save_change"><?php _e('Save','school-mgt');?></button>

								</div>

							</div>

						</form>
						</div>
						

				
			   
						</div>
						
						
						
							<?php 
					
				
							$user_info=get_userdata(get_current_user_id());
					$edit=1;
				 ?>
				 
				
				
						<div class="panel panel-white">
				<div class="panel-heading">
										<div class="panel-title"><?php _e('Account Settings ','school-mgt');?>	</div>
									</div>
									<div class="panel-body">
						<form class="form-horizontal" action="#" method="post">
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Address','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input id="address" class="form-control validate[required]" type="text"  name="address" value="<?php if($edit){ echo $user_info->address;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('City','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input id="city_name" class="form-control validate[required]" type="text"  name="city_name" value="<?php if($edit){ echo $user_info->city;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputstate" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('State','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input id="state_name" class="form-control validate[required]" type="text"  name="state_name" value="<?php if($edit){ echo $user_info->state;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Phone','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input id="phone" class="form-control validate[,custom[phone]] text-input" type="text"  name="phone" value="<?php if($edit){ echo $user_info->phone;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Email','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input id="email" class="form-control validate[required,custom[email]] text-input" type="text"  name="email" value="<?php if($edit){ echo $user_info->user_email;}?>">

								</div>

							</div>
							<div class="form-group">

								<div class="col-xs-offset-2 clo-md-8 col-sm-8 col-xs-12">

									<button type="submit" class="btn btn-success" name="profile_save_change"><?php _e('Save','school-mgt');?></button>

								</div>

							</div>
						</form>
						</div>
						</div>
					
						
						
					 </div>
					 
						
					<?php	
				if(!empty($user_meta))
				{
					
					?>
					 <div class="col-md-3 m-t-lg">
					 <div class="panel panel-white">
							<div class="panel-heading">
								<div class="panel-title"><?php echo $title; ?></div>
							</div>
						
							<div class="panel-body">
								<div class="team">
									
								
					<?php 
				
						  foreach($user_meta as $parentsdata)
						  {
							$parent=get_userdata($parentsdata);
							
							if($parent){
					?>
						
						<div class="team-member">
							<?php 
							//echo "Parent Data : ".$parentsdata;
							if($parentsdata){
								$umetadata=get_user_image($parentsdata);
							}
								//$umetadata=get_user_image($parentsdata);
									if(empty($umetadata['meta_value']) || $umetadata['meta_value'] == "")
									{//echo get_avatar($parentsdata,'46');
										echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle" />';
									}
									else
									echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';?></td>
									<span>
									<?php echo $parent->display_name;?> </span><br>
									<small>
									<?php
										if($title=='Parent')
										{
											echo $parent->relation;
										}
										if($title=='child')
										{
											echo "Class : ".get_class_name($parent->class_name) ;
										}
										?> 
									
									</small>
									
									</div>
									
									
							<?php
							}
								}?>
									
										
									</div>
								</div>
				
							</div>
						</div>
						<?php } ?>
					 </div>

				 
			<?php }
				else
				{
			?>
		<div class="col-md-9 m-t-lg">
			<div class="panel panel-white">
				<div class="panel-heading">
										<div class="panel-title"><?php _e('Account Settings','school-mgt');?> </div>
									</div>
									<div class="panel-body">
						<form class="form-horizontal" action="#" method="post">
								<div class="form-group">
									<label  class="control-label clo-md-2 col-sm-2 col-xs-12"></label>
									<div class="clo-md-8 col-sm-8 col-xs-12">	
										<p>
										<h4 class="bg-danger"><?php 
										if(isset($_REQUEST['sucess']))
										{ 
											if($_REQUEST['sucess']==1)
											{
												echo _e('Password successfully change','smgt_school'); 
											}
											
											
										}?></h4>
									</p>
									</div>
							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Name','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input type="Name" class="form-control" id="name" placeholder="Full Name" value="<?php echo $user->display_name; ?>" readonly>

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Name','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input type="Name" class="form-control" id="name" placeholder="Full Name" value="<?php echo $user->user_login; ?>" readonly>

								</div>

							</div>

							<div class="form-group">

								<label for="inputPassword" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Current Password','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input type="password" class="form-control" id="inputPassword" placeholder="Password" name="current_pass">

								</div>

							</div>
					<div class="form-group">

								<label for="inputPassword" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('New Password','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input type="password" class="validate[required] form-control" id="inputPassword" placeholder="New Password" name="new_pass">

								</div>

							</div><div class="form-group">

								<label for="inputPassword" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Conform Password','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input type="password" class="validate[required] form-control" id="inputPassword" placeholder="Conform Password" name="conform_pass">

								</div>

							</div>
							

							<div class="form-group">

								<div class="col-xs-offset-2 clo-md-8 col-sm-8 col-xs-12">

									<button type="submit" class="btn btn-success" name="save_change"><?php _e('Save','school-mgt');?></button>

								</div>

							</div>

						</form>
						</div>
						</div>
							
							<?php 
					
				
					
					$edit=1;
				 ?>
				 
				
				
						<div class="panel panel-white">
				<div class="panel-heading">
										<div class="panel-title"><?php _e('Account Settings ','school-mgt');?>	</div>
									</div>
									<div class="panel-body">
						<form class="form-horizontal" action="#" method="post">
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Address','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input id="address" class="form-control validate[required]" type="text"  name="address" value="<?php if($edit){ echo $user_info->address;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('City','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input id="city_name" class="form-control validate[required]" type="text"  name="city_name" value="<?php if($edit){ echo $user_info->city;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputstate" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('State','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input id="state_name" class="form-control validate[required]" type="text"  name="state_name" value="<?php if($edit){ echo $user_info->state;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Phone','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input id="phone" class="form-control validate[,custom[phone]] text-input" type="text"  name="phone" value="<?php if($edit){ echo $user_info->phone;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label clo-md-2 col-sm-2 col-xs-12"><?php _e('Email','school-mgt');?></label>

								<div class="clo-md-8 col-sm-8 col-xs-12">

									<input id="email" class="form-control validate[required,custom[email]] text-input" type="text"  name="email" value="<?php if($edit){ echo $user_info->user_email;}?>">

								</div>

							</div>
							<div class="form-group">

								<div class="col-xs-offset-2 clo-md-8 col-sm-8 col-xs-12">

									<button type="submit" class="btn btn-success" name="profile_save_change"><?php _e('Save','school-mgt');?></button>

								</div>

							</div>
						</form>
						</div>
						</div>
						
							
				
			   
						
			</div>
				<?php } ?>
 		</div>
		

		</div>
	</div>
</div>
<?php 
	if(isset($_POST['profile_save_change']))
	{
		
		$usermetadata=array(
						'address'=>$_POST['address'],
						'city'=>$_POST['city_name'],
						'state'=>$_POST['state_name'],
						'phone'=>$_POST['phone']);
	
		$userdata = array(
			'user_email'=>$_POST['email'],);
			
		$userdata['ID']=$user->ID;
		$result=update_user_profile($userdata,$usermetadata);
		if($result)
		{ 
			
			wp_safe_redirect(home_url()."?dashboard=user&page=account&action=edit" );
		}
	}

?>