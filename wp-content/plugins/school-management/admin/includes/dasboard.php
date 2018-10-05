<?php 
	// This is Dashboard at admin side!!!!!!!!! 
	$obj_attend=new Attendence_Manage();
	$all_notice = "";
	$args['post_type'] = 'notice';
	$args['posts_per_page'] = -1;
	$args['post_status'] = 'public';
	$q = new WP_Query();
	$all_notice = $q->query( $args );
	$notive_array = array ();
	if (! empty ( $all_notice )) {
		foreach ( $all_notice as $notice ) {
			 $notice_start_date=get_post_meta($notice->ID,'start_date',true);
			 $notice_end_date=get_post_meta($notice->ID,'end_date',true);
			$i=1;
			
			$notive_array [] = array (
					'title' => $notice->post_title,
					'start' => mysql2date('Y-m-d', $notice_start_date ),
					'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days'))
			);	
			
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
	?>
<script>
	
	 $(document).ready(function() {
	
		 $('#calendar').fullCalendar({
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
<div class="page-inner" style="min-height:1088px !important">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?>
		</h3>
	</div>
	<div id="main-wrapper">
		<div class="row">
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
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body teacher">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_usersdata('teacher'));?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'Teacher', 'school-mgt' ) );?></span>
						</div>
						
                        
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body parent">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_usersdata('parent'));?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'Parent', 'school-mgt' ) );?></span>
						</div>
						
                        
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body attendence">
						<div class="info-box-stats">
							<p class="counter"><?php echo $obj_attend->today_presents();?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'Today Attendence', 'school-mgt' ) );?></span>
						</div>
						
                        
					</div>
				</div>
			</div>
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
						<h3 class="panel-title"><?php _e('Noticeboard','school-mgt');?></h3>
						
					</div>
					<div class="panel-body">
					<?php
									  $args['post_type'] = 'notice';
					  $args['posts_per_page'] = 3;
					  $args['post_status'] = 'public';
					  $q = new WP_Query();
				$retrieve_class = $q->query( $args );
					foreach ($retrieve_class as $retrieved_data){ ?>
					<div class="events">
					<div class="calendar-event"> 
					<p>
					<?php  echo $retrieved_data->post_title;?>
					</p>
					</div>
					</div>
					<?php }?>
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
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-heading">
						<h3 class="panel-title"><?php _e('Report','school-mgt');?></h3>
					</div>
					<div class="panel-body">
					<div class="col-md-6">
					
					<div class="panel-body">
					<?php 
					global $wpdb;
					$table_attendance = $wpdb->prefix .'attendence';
					$table_class = $wpdb->prefix .'smgt_class';
					require_once SMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
					$report_1 =$wpdb->get_results("SELECT  at.class_id,
							SUM(case when `status` ='Present' then 1 else 0 end) as Present,
							SUM(case when `status` ='Absent' then 1 else 0 end) as Absent
							from $table_attendance as at,$table_class as cl where at.attendence_date >  DATE_SUB(NOW(), INTERVAL 1 WEEK) AND at.class_id = cl.class_id AND at.role_name = 'student' GROUP BY at.class_id") ;
					$chart_array = array();
					$chart_array[] = array(__('Class','school-mgt'),__('Present','school-mgt'),__('Absent','school-mgt'));
					if(!empty($report_1))
						foreach($report_1 as $result)
						{
					
							$class_id =get_class_name($result->class_id);
							$chart_array[] = array("$class_id",(int)$result->Present,(int)$result->Absent);
						}
					
					$options = Array(
							'title' => __('Last Week Attendance Report','school-mgt'),
							'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
							'legend' =>Array('position' => 'right',
									'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
					
							'hAxis' => Array(
									'title' =>  __('Class','school-mgt'),
									'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
									'textStyle' => Array('color' => '#222','fontSize' => 10),
									'maxAlternation' => 2
					
					
							),
							'vAxis' => Array(
									'title' =>  __('No of Student','school-mgt'),
									'minValue' => 0,
									'maxValue' => 5,
									'format' => '#',
									'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
									'textStyle' => Array('color' => '#222','fontSize' => 12)
							),
							'colors' => array('#22BAA0','#f25656')
							
					);
					
					$GoogleCharts = new GoogleCharts;
					if(!empty($report_1))					
					{	
						$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
					}
					else
						_e("There is not enough data to generate report",'school-mgt');
					 
					
					?>
					 <div id="chart_div" style="width: 100%; height: 500px;"></div>
  
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php 
			if(isset($chart))
			echo $chart;?>
		</script>
					</div>
					</div>
					<div class="col-md-6">
					<?php 
					global $wpdb;
					
					$table_attendance = $wpdb->prefix .'attendence';
					$table_class = $wpdb->prefix .'smgt_class';
					$report_2 =$wpdb->get_results("SELECT  at.class_id,
							SUM(case when `status` ='Present' then 1 else 0 end) as Present,
							SUM(case when `status` ='Absent' then 1 else 0 end) as Absent
							from $table_attendance as at,$table_class as cl where at.attendence_date >  DATE_SUB(NOW(), INTERVAL 1 MONTH) AND at.class_id = cl.class_id AND at.role_name = 'student' GROUP BY at.class_id") ;
					$chart_array = array();
					$chart_array[] = array('Class','Present','Absent');
					if(!empty($report_2))
						foreach($report_2 as $result)
						{
					
							$class_id =get_class_name($result->class_id);
							$chart_array[] = array("$class_id",(int)$result->Present,(int)$result->Absent);
						}
					
					//var_dump($chart_array);
					
					
					$options = Array(
							'title' => __('Last Month Attendance Report','school-mgt'),
							'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
							'legend' =>Array('position' => 'right',
									'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
					
							'hAxis' => Array(
									'title' =>  __('Class','school-mgt'),
									'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
									'textStyle' => Array('color' => '#222','fontSize' => 10),
									'maxAlternation' => 2
					
					
							),
							'vAxis' => Array(
									'title' =>  __('No of Student','school-mgt'),
									'minValue' => 0,
									'maxValue' => 5,
									'format' => '#',
									'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
									'textStyle' => Array('color' => '#222','fontSize' => 12)
							),
							'colors' => array('#22BAA0','#f25656')
					);
					
					$GoogleCharts = new GoogleCharts;
					if(!empty($report_2))					
					{	
						$chart = $GoogleCharts->load( 'column' , 'chart_div1' )->get( $chart_array , $options );
					}
					else
						_e("There is not enough data to generate report",'school-mgt');
					 
					
					?>
					 <div id="chart_div1" style="width: 100%; height: 500px;"></div>
  
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php 
			if(isset($chart))
			echo $chart;?>
		</script>
					</div>
					</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
<?php 
?>