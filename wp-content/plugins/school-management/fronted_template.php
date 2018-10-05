<?php
// render teplate

if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf')
{
	$sudent_id = $_REQUEST['student'];
	downlosd_smgt_result_pdf($sudent_id);
}
$obj_attend = new Attendence_Manage ();
$school_obj = new School_Management ( get_current_user_id () );


$notive_array = array ();

if($school_obj->role=='student'){
	if (! empty ( $school_obj->notice )) {
		foreach ( $school_obj->notice as $noticeid ) {
			$notice=get_post($noticeid);
			 $notice_start_date=get_post_meta($notice->ID,'start_date',true);
			$notice_end_date=get_post_meta($notice->ID,'end_date',true);
			//echo $notice->post_title;
				$i=1;
				
				$notive_array [] = array (
						'title' => $notice->post_title,
						'start' => mysql2date('Y-m-d', $notice_start_date ),
						'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days'))
				);	
		}
	}
}
else
{
	if (! empty ( $school_obj->notice )) {
		foreach ( $school_obj->notice as $notice ) {
			 $notice_start_date=get_post_meta($notice->ID,'start_date',true);
			$notice_end_date=get_post_meta($notice->ID,'end_date',true);
			//echo $notice->post_title;
				$i=1;
				
				$notive_array [] = array (
						'title' => $notice->post_title,
						'start' => mysql2date('Y-m-d', $notice_start_date ),
						'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days'))
				);	
		}
	}
}
$holiday_list = get_all_data ( 'holiday' );
if (! empty ( $holiday_list )) {
	foreach ( $holiday_list as $notice ) {
		$notice_start_date=$notice->date;
		$notice_end_date=$notice->end_date;
		//echo $notice->post_title;
		$i=1;
			
		$notive_array [] = array (
				'title' => $notice->holiday_title,
				'start' => mysql2date('Y-m-d', $notice_start_date ),
				'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days')),
				'color' => '#12AFCB'
		);
	}
}
//var_dump($notive_array);
//exit;
if (! is_user_logged_in ()) {
	$page_id = get_option ( 'smgt_login_page' );
	
	wp_redirect ( home_url () . "?page_id=" . $page_id );
}
if (is_super_admin ()) {
	wp_redirect ( admin_url () . 'admin.php?page=smgt_school' );
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dataTables.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dataTables.editor.min.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dataTables.tableTools.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/jquery-ui.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/font-awesome.min.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/popup.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/style.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/fullcalendar.css'; ?>">

<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/bootstrap.min.css'; ?>">	
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/white.css'; ?>">
    
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/schoolmgt.min.css'; ?>">
<?php if (is_rtl()) {?>
			<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/bootstrap-rtl.min.css'; ?>">
		<?php  }?>

<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/simple-line-icons.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/css/validationEngine.jquery.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/school-responsive.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dataTables.responsive.css'; ?>">


<?php 
if(@file_exists(get_stylesheet_directory().'/css/smgt-customcss.css')) {
	?>
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/smgt-customcss.css" type="text/css" />
	<?php 
}
else 
{
	?>
	<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/smgt-customcss.css'; ?>">
	<?php 
}
?>
<!--<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/jquery-1.11.1.min.js'; ?>"></script>-->
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/accordian/jquery-1.10.2.js'; ?>"></script>


<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/jquery.timeago.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/jquery-ui.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/moment.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/fullcalendar.min.js'; ?>"></script>
<?php /*--------Full calendar multilanguage---------*/
	$lancode=get_locale();
	$code=substr($lancode,0,2);?>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/calendar-lang/'.$code.'.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/jquery.dataTables.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/dataTables.tableTools.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/dataTables.editor.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/dataTables.responsive.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/bootstrap.min.js'; ?>"></script>

<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
<script>
jQuery(document).ready(function() {
	
	jQuery('#calendar').fullCalendar({
			 header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: <?php echo json_encode($notive_array);?>
		});

		 
	});
</script>

</head>
<body class="schoo-management-content">
  <?php
 // echo get_stylesheet_directory().'/css/smgt-customcss.css';
		$user = wp_get_current_user ();
		?>
  <div class="container-fluid mainpage">
  <div class="navbar">
	
	<div class="col-md-8 col-sm-8 col-xs-6">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" />
		<span><?php echo get_option( 'smgt_school_name' );?> </span>
		</h3></div>
		
		<ul class="nav navbar-right col-md-4 col-sm-4 col-xs-6">
				
				<!-- BEGIN USER LOGIN DROPDOWN -->
				<li class="dropdown"><a data-toggle="dropdown"
					class="dropdown-toggle" href="javascript:;">
						<?php
						$umetadata = get_user_image ( $user->ID );
						if (empty ( $umetadata ['meta_value'] )){
							echo '<img src='.get_option( 'smgt_student_thumb' ).' height="40px" width="40px" class="img-circle" />';
						}
						else
							echo '<img src=' . $umetadata ['meta_value'] . ' height="40px" width="40px" class="img-circle"/>';
						?>
							<span>	<?php echo $user->display_name;?> </span> <b class="caret"></b>
				</a>
					<ul class="dropdown-menu extended logout">
						<li><a href="?dashboard=user&page=account"><i class="fa fa-user"></i>
								<?php _e('My Profile','school-mgt');?></a></li>
						<li><a href="<?php echo wp_logout_url(home_url()); ?>"><i
								class="fa fa-sign-out m-r-xs"></i><?php _e('Log Out','school-mgt');?> </a></li>
					</ul></li>
				<!-- END USER LOGIN DROPDOWN -->
			</ul>
	
	</div>
	</div>
	<div class="container-fluid">
	<div class="row">
		<div class="col-sm-2 nopadding school_left nav-side-menu">
		 <div class="brand"><?php _e('Menu','school-mgt');?>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i></div>
   
			<!--  Left Side -->
 <?php
	
	//$menu = smgt_menu ();
	$menu = get_option( 'smgt_access_right');
	
	$class = "";
	if (! isset ( $_REQUEST ['page'] ))	
		$class = 'class = "active"';
		// print_r($menu); 	?>
  <ul class="nav nav-pills nav-stacked collapse out" id="menu-content">
  
				<li><a href="<?php echo site_url();?>"><span class="icone"><img src="<?php echo plugins_url( 'school-management/assets/images/icons/home.png' )?>"/></span><span class="title"><?php _e('Home','school-mgt');?></span></a></li>
				<li <?php echo $class;?>>
					<a href="?dashboard=user">
						<span class="icone"><img src="<?php echo plugins_url( 'school-management/assets/images/icons/dashboard.png' )?>"/></span>
						<span class="title"><?php _e('Dashboard','school-mgt');?></span>
					</a>
			  </li>
        <?php
								
								$role = $school_obj->role;
								foreach ( $menu as $key=>$value ) {
									if ($value [$role]) {
										if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $value ['page_link'])
											$class = 'class = "active"';
										else
											$class = "";
										echo '<li ' . $class . '><a href="?dashboard=user&page=' . $value ['page_link'] . '" ><span class="icone"> <img src="' .$value ['menu_icone'].'" /></span><span class="title">'.change_menutitle($key).'</span></a></li>';
									}
									?>
									
        
        <?php
								}
								 //$value ['menu_title'] ?>
								
							
      </ul>
		</div>
		
		<div class="page-inner">
		<div class="right_side <?php if(isset($_REQUEST['page']))echo $_REQUEST['page'];?>">
		   <?php
		
		if (isset ( $_REQUEST ['page'] )) {
			require_once SMS_PLUGIN_DIR . '/template/' . $_REQUEST ['page'] . '.php';
			return false;
		}
		
		?>
		<!---start new dashboard------>
			<div class="row">
			<?php $student=get_option('smgt_enable_total_student');
			if($student==1){?>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body student">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_usersdata('student'));?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'Student', 'school-mgt' ) );?></span>
						</div>
					</div>
				</div>
			</div>
			<?php }
			$teacher=get_option('smgt_enable_total_teacher');
			if($teacher==1){ ?>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body teacher">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_usersdata('teacher'));?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'teacher', 'school-mgt' ) );?></span>
						</div>
						
                        
					</div>
				</div>
			</div>
			<?php } 
			$parent=get_option('smgt_enable_total_parent');
			if($parent==1){ ?>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body parent">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_usersdata('parent'));?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'parent', 'school-mgt' ) );?></span>
						</div>
						
                        
					</div>
				</div>
			</div>
			<?php }
			$attendance=get_option('smgt_enable_total_attendance');
			if($attendance==1){ ?>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body attendence">
						<div class="info-box-stats">
							<p class="counter"><?php echo $obj_attend->today_presents();?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'Today attendence', 'school-mgt' ) );?></span>
						</div>
						
                        
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="row">
			<div class="col-md-8">
				<div class="panel panel-white">
					<div class="panel-body">
						<div id="calendar"></div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-white">
					<div class="panel-heading">
						<h3 class="panel-title"><?php _e('Notice','school-mgt');?></h3>
						
					</div>
					<div class="panel-body">
					<?php
									  $args['post_type'] = 'notice';
					  $args['posts_per_page'] = 3;
					  $args['post_status'] = 'public';
					  $q = new WP_Query();
				//$retrieve_class =$school_obj->notice;
					$retrieve_class= $school_obj->notice_board($school_obj->role,3);
				//$retrieve_class = $q->query( $args );
				if($school_obj->role=='student'){
		 	foreach ($retrieve_class as $postid){
				$retrieved_data=get_post($postid);
				?>
						<div class="events">
					<div class="calendar-event"> 
					<p>
					<?php  echo $retrieved_data->post_title;?>
					</p>
					</div>
					</div>
				<?php }
			}
			else{
					
					foreach ($retrieve_class as $retrieved_data){ ?>
					<div class="events">
					<div class="calendar-event"> 
					<p>
					<?php  echo $retrieved_data->post_title;?>
					</p>
					</div>
					</div>
					<?php } 
				}	?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-white">
					<div class="panel-heading">
						<h3 class="panel-title"><?php _e('Holiday List','school-mgt');?></h3>
						
					</div>
					<div class="panel-body">
					<?php
						$tablename="holiday";			
					  $retrieve_class = get_all_data($tablename);
					
					foreach ($retrieve_class as $retrieved_data){ ?>
					<div class="events">
					<div class="calendar-event"> 
					<p>
					<?php  echo $retrieved_data->holiday_title;?>
					</p>
					</div>
					</div>
					<?php }?>
					</div>
				</div>
			</div>
		</div>	
		<!---End new dashboard------>
		
  <?php
		
		if($school_obj->role == 'teacher')
		{
		
		?>
			<div class="panel1"> 	
			<div class="row dashboard">

				
				

			<div class="col-lg-12 col-md-12">
				<div class="panel panel-white">
					<div class="panel-heading">
                    	<h4 class="panel-title"><?php _e('My Time Table','school-mgt')?></h4>
                    </div>
                     <div class="panel-body">
            <table class="table table-bordered" cellspacing="0" cellpadding="0" border="0">
        <?php 
        $obj_route = new Class_routine();
        $i = 0;
        $i++;
		foreach(sgmt_day_list() as $daykey => $dayname)
		{
		?>
		<tr>
        <th width="100"><?php echo $dayname;?></th>
        <td>
        	 <?php
			 	$period = $obj_route->get_periad_by_teacher(get_current_user_id(),$daykey);
				if(!empty($period))
					foreach($period as $period_data)
					{
						echo '<button class="btn btn-primary"><span class="period_box" id='.$period_data->route_id.'>'.get_single_subject_name($period_data->subject_id);
						echo '<span class="time"> ('.$period_data->start_time.'- '.$period_data->end_time.') </span>';
						echo '<span>'.get_class_name($period_data->class_id).'</span>';
						echo '</span></button>';
						
					}
			 ?>
			
        </td>
        </tr>
		<?php	
		}
		?>
        </table>
         </div>
				</div>
			</div>



			</div>


		


		</div>
		<?php }?>
		</div>
		</div>
	</div>

</div>


</body>
</html>

<?php ?>