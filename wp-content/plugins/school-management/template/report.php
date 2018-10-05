	<?php 
	// This is Class at admin side!!!!!!!!! 
	//----------Add-update record---------------------
$active_tab = isset($_GET['tab'])?$_GET['tab']:'report1';
$obj_marks = new Marks_Manage();
if($active_tab == 'report1')
{
$chart_array = array();
$chart_array[] = array( __('Class','school-mgt'),__('No. of Student Fail','school-mgt'));

if(isset($_REQUEST['report_1']))
{
	global $wpdb;
	$table_marks = $wpdb->prefix .'marks';
	$table_users = $wpdb->prefix .'users';
	$exam_id = $_REQUEST['exam_id'];
	$class_id = $_REQUEST['class_id'];
	if(isset($_REQUEST['class_section']) && $_REQUEST['class_section']!=""){
			$section_id = $_REQUEST['class_section'];
		
		
		$report_1 =$wpdb->get_results("SELECT * , count( student_id ) as count
				FROM $table_marks as m, $table_users as u
				WHERE m.marks <40
				AND m.exam_id = $exam_id
				AND m.Class_id = $class_id
				AND m.section_id = $section_id
				AND m.student_id = u.id
				GROUP BY subject_id");
		}
		else
		{
			$report_1 =$wpdb->get_results("SELECT * , count( student_id ) as count
				FROM $table_marks as m, $table_users as u
				WHERE m.marks <40
				AND m.exam_id = $exam_id
				AND m.Class_id = $class_id
				AND m.student_id = u.id
				GROUP BY subject_id");
		}
if(!empty($report_1))
foreach($report_1 as $result)
{
	
	$subject =get_single_subject_name($result->subject_id);
	$chart_array[] = array("$subject",(int)$result->count);
}

$options = Array(
		'title' => __('Exam Failed Report','school-mgt'),
		'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
		'legend' =>Array('position' => 'right',
				'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
			
		'hAxis' => Array(
				'title' =>  __('Subject','school-mgt'),
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
		'colors' => array('#22BAA0')
);

}
}
if($active_tab == 'report2')
{
	$chart_array[] = array(__('Class','school-mgt'),__('Present','school-mgt'),__('Absent','school-mgt'));
if(isset($_REQUEST['report_2']))
{
	
	global $wpdb;
	$table_attendance = $wpdb->prefix .'attendence';
	$table_class = $wpdb->prefix .'smgt_class';
	$sdate = $_REQUEST['sdate'];
	$edate = $_REQUEST['edate'];
	
	$report_2 =$wpdb->get_results("SELECT  at.class_id, 
SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
SUM(case when `status` ='Absent' then 1 else 0 end) as Absent 
from $table_attendance as at,$table_class as cl where `attendence_date` BETWEEN '$sdate' AND '$edate' AND at.class_id = cl.class_id AND at.role_name = 'student' GROUP BY at.class_id") ;
	if(!empty($report_2))
		foreach($report_2 as $result)
		{

			$class_id =get_class_name($result->class_id);
			$chart_array[] = array("$class_id",(int)$result->Present,(int)$result->Absent);
		}

	$options = Array(
			'title' => __('Attendance Report','school-mgt'),
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

}

}

if($active_tab == 'report3')
{
	$chart_array[] = array(__('Teacher','school-mgt'),__('fail','school-mgt'));
	
	

		global $wpdb;
		
		$table_subject = $wpdb->prefix .'subject';
		$table_name_mark = $wpdb->prefix .'marks';
		$table_name_users = $wpdb->prefix .'users';
		
		$report_3 =$wpdb->get_results("SELECT sb.sub_name,sb.subid,sb.teacher_id,count(mark.student_id) as count FROM
				 $table_subject as sb,
				$table_name_mark as mark ,
				$table_name_users as u
				WHERE sb.subid=mark.subject_id AND mark.marks < 40 AND u.id = sb.teacher_id group by mark.subject_id") ;
		if(!empty($report_3))
			foreach($report_3 as $result)
			{

				$teacher_name =get_display_name($result->teacher_id);
				
				$chart_array[] = array("$teacher_name",(int)$result->count);
			}

		$options = Array(
				'title' => __('Teacher Perfomance Report','school-mgt'),
				'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),

				'hAxis' => Array(
						'title' =>  __('Teacher Name','school-mgt'),
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
				'colors' => array('#22BAA0')
		);

	



}
require_once SMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
$GoogleCharts = new GoogleCharts;
?>	
	<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="invoice_data">
     </div>
     
    </div>
    </div> 
    
</div>
<!-- End POP-UP Code -->
<div class="panel panel-white">
	<div class="panel-body"> 
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		  <li class="<?php if($active_tab=='report1'){?>active<?php }?>">
			  <a href="?dashboard=user&page=report&tab=report1">
				 <i class="fa fa-align-justify"></i> <?php _e('Student Failed Report', 'school-mgt'); ?></a>
			  </a>
		  </li>
		   <li class="<?php if($active_tab=='report2'){?>active<?php }?>">
			  <a href="?dashboard=user&page=report&tab=report2">
				 <i class="fa fa-align-justify"></i> <?php _e('Attendance Report', 'school-mgt'); ?></a>
			  </a>
		  </li>
		  <li class="<?php if($active_tab=='report3'){?>active<?php }?>">
			  <a href="?dashboard=user&page=report&tab=report3">
				 <i class="fa fa-align-justify"></i> <?php _e('Teacher Performance Report', 'school-mgt'); ?></a>
			  </a>
		  </li>
		  <li class="<?php if($active_tab=='report4'){?>active<?php }?>">
			  <a href="?dashboard=user&page=report&tab=report4">
				 <i class="fa fa-align-justify"></i> <?php _e('Fee Payment Report', 'school-mgt'); ?></a>
			  </a>
		  </li>
		  <li class="<?php if($active_tab=='report5'){?>active<?php }?>">
			  <a href="?dashboard=user&page=report&tab=report5">
				 <i class="fa fa-align-justify"></i> <?php _e('Result Report', 'school-mgt'); ?></a>
			  </a>
		  </li>
	  </ul>
	
    <?php 
    if($active_tab == 'report1')
    {
		$chart="";
    	?><script type="text/javascript">

$(document).ready(function() {
	
	 $('#failed_report').validationEngine();
	 $('#sdate').datepicker({dateFormat: "yy-mm-dd"}); 
	 $('#edate').datepicker({dateFormat: "yy-mm-dd"}); 
	
} );


</script>
		<div class="panel-body">
    	 <form method="post" id="failed_report">  
    <div class="form-group col-md-3">
    	<label for="exam_id"><?php _e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
        <?php
					$tablename="exam"; 
					$retrieve_class = get_all_data($tablename);?>
            	<select name="exam_id" class="form-control validate[required]">
                	<option value=" "><?php _e('Select Exam Name','school-mgt');?></option>
                    <?php
					$exam_id= isset($_REQUEST['exam_id'])?$_REQUEST['exam_id']:'';
					foreach($retrieve_class as $retrieved_data)
					{
					?>
                    <option value="<?php echo $retrieved_data->exam_id;?>" <?php echo selected($exam_id,$retrieved_data->exam_id);?>><?php echo $retrieved_data->exam_name;?></option>
					<?php	
					}
					?>
                </select>
    </div>
    <div class="form-group col-md-3">
    	<label for="class_id"><?php _e('Select Class','school-mgt');?><span class="require-field">*</span></label>
       <select name="class_id"  id="class_list" class="form-control validate[required]">
                	<option value=" "><?php _e('Select Class Name','school-mgt');?></option>
                    <?php
					$select_class= isset($_REQUEST['class_id'])?$_REQUEST['class_id']:'';
					  foreach(get_allclass() as $classdata)
					  {  
					  ?>
					   <option  value="<?php echo $classdata['class_id'];?>" <?php echo selected($select_class,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>
				 <?php }?>
                </select>
    </div>
	<div class="form-group col-md-3">
			<label for="class_id"><?php _e('Select Section','school-mgt');?></label>			
			<?php 
			$class_section="";
			if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
					<select name="class_section" class="form-control" id="class_section">
                        	<option value=""><?php _e('Select Class Section','school-mgt');?></option>
						<?php if(isset($_REQUEST['class_section'])){
								echo $class_section=$_REQUEST['class_section']; 
								foreach(smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							}?>		
	
                    </select>
		</div>
     <div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" name="report_1" Value="<?php _e('Go','school-mgt');?>"  class="btn btn-info"/>
    </div>
    	
    	</form>
		</div>
    	 <div class="clearfix"> </div>
    	  <div class="clearfix"> </div>
    	  <?php if(isset($_REQUEST['report_1']))
    	  {
    	  	if(!empty($report_1))
    	  	{	$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
    	  }
    	  else 
    	  	echo "result not found";
    	  
    	  }
    	  	?>
    	 <div id="chart_div" style="width: 100%; height: 500px;"></div>
  
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php echo $chart;?>
		</script>
    	<?php 
    }
    if($active_tab == 'report2')
    {
    ?>
	<script type="text/javascript">

$(document).ready(function() {
	 $('#attendance_report').validationEngine();
	
	 $('#sdate').datepicker({dateFormat: "yy-mm-dd"}); 
	 $('#edate').datepicker({dateFormat: "yy-mm-dd"}); 
	
} );


</script>
    <div class="clearfix"> </div>
     <div class="panel-body" id="attendance_report">
	 <form method="post">  
    <div class="form-group col-md-3">
    	<label for="exam_id"><?php _e('Strat Date','school-mgt');?></label>
       
					
            	<input type="text"  id="sdate" class="form-control" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo smgt_getdate_in_input_box($_REQUEST['sdate']);else echo date('Y-m-d');?>">
            	
    </div>
    <div class="form-group col-md-3">
    	<label for="exam_id"><?php _e('End Date','school-mgt');?></label>
			<input type="text"  id="edate" class="form-control" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo smgt_getdate_in_input_box($_REQUEST['edate']);else echo date('Y-m-d');?>">
            	
    </div>
    <div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" name="report_2" Value="<?php _e('Go','school-mgt');?>"  class="btn btn-info"/>
    </div>
    	
    	</form></div>
    	 <div class="clearfix"> </div>
    	  <div class="clearfix"> </div>
    	  <?php if(isset($_REQUEST['report_2']))
    	  {
    	  	if(!empty($report_2))
    	  	{	$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
    	  }
    	  else 
    	  	_e('result not found','school-mgt');
    	  
    	  }
    	  	?>
    	 <div id="chart_div" style="width: 100%; height: 500px;"></div>
  
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php echo $chart;?>
		</script>
    <?php 
    }
    if($active_tab == 'report3')
    {
    	?>
    	  <div class="clearfix"> </div>
    	 <?php 
    	  	if(!empty($report_3))
    	  	{	$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
    	  }
    	  else 
    	  	_e('result not found','school-mgt');
    	  ?>
    	 <div id="chart_div" style="width: 100%; height: 500px;"></div>
  
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php echo $chart;?>
		</script>
    	<?php } 
		if($active_tab == 'report4')
    {
    	?>
<script type="text/javascript">

$(document).ready(function() {
	
	$('#fee_payment_report').validationEngine();
	 $('#sdate').datepicker({dateFormat: "yy-mm-dd"}); 
	 $('#edate').datepicker({dateFormat: "yy-mm-dd"}); 
	 $('#payment_report').DataTable({
        responsive: true
    });
});
</script>
		<div class="panel-body">
    	 <form method="post" id="fee_payment_report">  
    
    <div class="form-group col-md-2">
    	<label for="class_id"><?php _e('Select Class','school-mgt');?><span class="require-field">*</span></label>
       <select name="class_id"  id="class_list" class="form-control load_fees validate[required]">
					<?php 
						$select_class = isset($_REQUEST['class_id'])?$_REQUEST['class_id']:'';
					?>
                	<option value=" "><?php _e('Select Class Name','school-mgt');?></option>
                    <?php
					  foreach(get_allclass() as $classdata)
					  {  
					  ?>
					   <option  value="<?php echo $classdata['class_id'];?>" <?php echo selected($select_class,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>
				 <?php }?>
                </select>
    </div>
	<div class="form-group col-md-2">
			<label for="class_id"><?php _e('Select Section','school-mgt');?></label>			
			<?php 
			$class_section="";
			if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
					<select name="class_section" class="form-control" id="class_section">
                        	<option value=""><?php _e('Select Class Section','school-mgt');?></option>
						<?php if(isset($_REQUEST['class_section'])){
								echo $class_section=$_REQUEST['class_section']; 
								foreach(smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							}?>		
	
                    </select>
		</div>
	<div class="form-group col-md-2">
    	<label for="class_id"><?php _e('Fee Type','school-mgt');?><span class="require-field">*</span></label>
       <select id="fees_data" class="form-control validate[required]" name="fees_id">
				<option value=" "><?php _e('Select Fee Type','school-mgt');?></option>
				<?php 
					if(isset($_REQUEST['fees_id']))
					{
						echo '<option value="'.$_REQUEST['fees_id'].'" '.selected($_REQUEST['fees_id'],$_REQUEST['fees_id']).'>'.get_fees_term_name($_REQUEST['fees_id']).'</option>';
					}
				?>
		</select>
    </div>
	<div class="form-group col-md-2">
    	<label for="fee_status"><?php _e('Payment Status','school-mgt');?><span class="require-field">*</span></label>
       <select id="fee_status" class="form-control validate[required]" name="fee_status">
	   <?php 
			$select_payment = isset($_REQUEST['fee_status'])?$_REQUEST['fee_status']:'';?>
				<option value=" "><?php _e('Select Payment Status','school-mgt');?></option>
				<option value="0" <?php echo selected($select_payment,0);?>><?php _e('Not Paid','school-mgt');?></option>
				<option value="1" <?php echo selected($select_payment,1);?>><?php _e('Partially Paid','school-mgt');?></option>
				<option value="2" <?php echo selected($select_payment,2);?>><?php _e('Fully paid','school-mgt');?></option>
		</select>
    </div>
	<!--<div class="form-group col-md-2">
    	<label for="fee_year"><?php _e('Year','school-mgt');?></label>
		
       <select id="fee_year" class="form-control" name="fee_year">
	   
				<option value=" "><?php _e('Select year','school-mgt');?></option>
				<?php $select_year = isset($_REQUEST['fee_year'])?$_REQUEST['fee_year']:'';
				$fee_payment_data = get_feepayment_all_record();
				if(!empty($fee_payment_data))
				{
					foreach($fee_payment_data as $retrive_data)
					{
						echo '<option value="'.$retrive_data->start_year.'-'.$retrive_data->end_year.'" '.selected($select_year,$retrive_data->start_year.'-'.$retrive_data->end_year).'>'.$retrive_data->start_year.'-'.$retrive_data->end_year.'</option>';
					}
				} ?>				
		</select>
    </div>-->
	 <div class="form-group col-md-2 date-field">
    	<label for="exam_id"><?php _e('Strat Date','school-mgt');?></label>
       
					
            	<input type="text"  id="sdate" class="form-control" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>">
            	
    </div>
    <div class="form-group col-md-2 date-field">
    	<label for="exam_id"><?php _e('End Date','school-mgt');?></label>
			<input type="text"  id="edate" class="form-control" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>">
            	
    </div>
     <div class="form-group col-md-1 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" name="report_4" Value="<?php _e('Go','school-mgt');?>"  class="btn btn-info"/>
    </div>
    	
    	</form>
		</div>
    	  <div class="clearfix"> </div>
    	<?php
			if(isset($_POST['report_4']))
			{
				if($_POST['class_id']!=' ' && $_POST['fees_id']!=' ' && $_POST['sdate']!=' ' && $_POST['edate']!=' '){
				$class_id = $_POST['class_id'];
				$section_id=0;
				if(isset($_POST['class_section']))
					$section_id = $_POST['class_section'];
				$fee_term =$_POST['fees_id'];
				$payment_status = $_POST['fee_status'];
			
				$sdate = $_POST['sdate'];
				$edate = $_POST['edate'];
				$result_feereport = get_payment_report($class_id,$fee_term,$payment_status,$sdate,$edate,$section_id);
				}
			?>
			<div class="table-responsive">
        <table id="payment_report" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>                
                <th><?php _e('Fee Type','school-mgt');?></th>  
				<th><?php _e('Student Name','school-mgt');?></th>  
				<th><?php _e('Roll No','school-mgt');?></th>  
                <th><?php _e('Class','school-mgt');?> </th>  
				<th><?php _e('Payment <BR>Status','school-mgt'); ?></th>
                <th><?php _e('Amount','school-mgt');?></th>
				 <th><?php _e('Due <BR> Amount','school-mgt');?></th>
                <th><?php _e('Description','school-mgt');?></th>  
				<th><?php _e('Year','school-mgt');?></th>
                <th><?php _e('Action','school-mgt');?></th>                 
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th><?php _e('Fee Type','school-mgt');?></th>  
				<th><?php _e('Student Name','school-mgt');?></th>
				<th><?php _e('Roll No','school-mgt');?></th>  
                <th><?php _e('Class','school-mgt');?> </th>  
				<th><?php _e('Payment <BR>Status','school-mgt'); ?></th>
                <th><?php _e('Amount','school-mgt');?></th>
				 <th><?php _e('Due <BR> Amount','school-mgt');?></th>
                <th><?php _e('Description','school-mgt');?></th> 
				<th><?php _e('Year','school-mgt');?></th>
                <th><?php _e('Action','school-mgt');?></th>         
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
			if(!empty($result_feereport))
		 	foreach ($result_feereport as $retrieved_data){ 
			
		 ?>
            <tr>
				 <td><?php echo get_fees_term_name($retrieved_data->fees_id);?></td>
				 <td><?php echo get_user_name_byid($retrieved_data->student_id);?></td>
				  <td><?php echo get_user_meta($retrieved_data->student_id, 'roll_id',true);?></td>
				  <td><?php echo get_class_name($retrieved_data->class_id);?></td>
				  <td>
					<?php 
					echo "<span class='btn btn-success btn-xs'>";
					echo get_payment_status($retrieved_data->fees_pay_id);
					echo "</span>";
						
					?>
				</td>
				   <td><?php echo $retrieved_data->total_amount;?></td>
				    <td><?php echo $retrieved_data->total_amount-$retrieved_data->fees_paid_amount;?></td>
					 <td><?php echo $retrieved_data->description;?></td>
					 <td><?php echo $retrieved_data->start_year.'-'.$retrieved_data->end_year;?></td>
              
               <td>
			
				<a href="#" class="show-view-payment-popup btn btn-default" idtest="<?php echo $retrieved_data->fees_pay_id; ?>" view_type="view_payment"><?php _e('View','school-mgt');?></a>
              
            </tr>
            <?php } ?>
     
        </tbody>
        
        </table>
       </div>
			<?php
			}
		?>
    	<?php }
	if($active_tab == 'report5')
    { ?>

<script type="text/javascript">
$(document).ready(function() {
	 $('#result_report').validationEngine();
});
</script>
		<div class="panel-body">
    	 <form method="post" id="result_report">  
    
    <div class="form-group col-md-3">
    	<label for="exam_id"><?php _e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
        <?php
					$tablename="exam"; 
					$retrieve_class = get_all_data($tablename);?>
					<?php
					$exam_id="";
					if(isset($_REQUEST['exam_id'])){
								$exam_id=$_REQUEST['exam_id']; 
					} ?>
            	<select name="exam_id" class="form-control validate[required]">
                	<option value=" "><?php _e('Select Exam Name','school-mgt');?></option>
                    <?php
					foreach($retrieve_class as $retrieved_data)
					{
					?>
                    <option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($retrieved_data->exam_id,$exam_id)?>><?php echo $retrieved_data->exam_name;?></option>
					<?php	
					}
					?>
                </select>
    </div>
    <div class="form-group col-md-3">
    	<label for="class_id"><?php _e('Select Class','school-mgt');?><span class="require-field">*</span></label>
       <select name="class_id"  id="class_list" class="form-control validate[required]">
					<?php $class_id="";
					if(isset($_REQUEST['class_id'])){
						$class_id=$_REQUEST['class_id'];
						}?>
                	<option value=" "><?php _e('Select Class Name','school-mgt');?></option>
                    <?php
					  foreach(get_allclass() as $classdata)
					  {  
					  ?>
					   <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?> ><?php echo $classdata['class_name'];?></option>
				 <?php }?>
                </select>
    </div>
	<div class="form-group col-md-3">
			<label for="class_id"><?php _e('Select Section','school-mgt');?></label>			
			<?php 
			$class_section="";
			if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
					<select name="class_section" class="form-control" id="class_section">
                        	<option value=""><?php _e('Select Class Section','school-mgt');?></option>
						<?php if(isset($_REQUEST['class_section'])){
								echo $class_section=$_REQUEST['class_section']; 
								foreach(smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							}?>		
	
                    </select>
		</div>
     <div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" name="report_5" Value="<?php _e('Go','school-mgt');?>"  class="btn btn-info"/>
    </div>
    	
    	</form>
		</div>
    	<div class="panel-body clearfix">
		
		<?php if(isset($_POST['report_5']))
			{ 
				$exam_id=$_REQUEST['exam_id'];
				$class_id=$_REQUEST['class_id'];
				if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != ""){
					$subject_list = $obj_marks->student_subject($_REQUEST['class_id'],$_REQUEST['class_section']);
					$exlude_id = smgt_approve_student_list();
					$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
									 'meta_query'=> array(array('key' => 'class_name','value' =>$_REQUEST['class_id'],'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
				}
				else
				{ 
					$subject_list = $obj_marks->student_subject($_REQUEST['class_id']);
					$exlude_id = smgt_approve_student_list();
					$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $_REQUEST['class_id'],'role'=>'student','exclude'=>$exlude_id));
				} ?>
				
			
		<div class="table-responsive">
		<table class="table col-md-12">
    	<tr>	
	       	<th><?php _e('Roll No.','school-mgt');?></th>
        	<th><?php _e('Name','school-mgt');?></th>         
			<?php 
			   if(!empty($subject_list))
				{			
					foreach($subject_list as $sub_id)
					{
						
						echo "<th> ".$sub_id->sub_name." </th>";
					}
				} ?>
				<th><?php _e('Total','school-mgt');?></th>
				<th>&nbsp;</th>
				</tr>
				<?php
	foreach($student as $user)
	{
			
		
		echo "<tr>";
		echo '<td>'.$user->roll_id.'</td>';
		echo '<td><span>' .get_user_name_byid($user->ID). '</span></td>';
		$total=0;
		if(!empty($subject_list))
		{		
			
			foreach($subject_list as $sub_id)
			{
				$mark_detail = $obj_marks->subject_makrs_detail_byuser($exam_id,$class_id,$sub_id->subid,$user->ID);
				if($mark_detail)
			{
				$mark_id=$mark_detail->mark_id;
				$marks=$mark_detail->marks;
				$total+=$marks;
				
				
			}
			else
			{
				$marks=0;
				$attendance=0;
				$marks_comment="";
				
				$mark_id="0";
			}
				//echo " ".$sub_id->subid." ";
				echo '<td>'.$marks.'</td>';
			}
			echo '<td>'.$total.'</td>';
		}
		else
		{
			echo '<td>'.$total.'</td>';
		}
		echo "</tr>";
	} 
			}?>
				</table>
				</div>
				</div>
			<?php
			}
		?>
 		</div>
		
 	</div>