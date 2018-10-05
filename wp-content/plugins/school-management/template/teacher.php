<?php 
//teaher
?>

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
	<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="active">
          <a href="#child" role="tab" data-toggle="tab">
             <i class="fa fa-align-justify"></i> <?php _e('Attendance', 'school-mgt'); ?></a>
          </a>
      </li>
</ul>
   

<div class="tab-content">
      
        	<div class="panel-body">
<form name="wcwm_report" action="" method="post">
<input type="hidden" name="attendance" value=1> 
<input type="hidden" name="user_id" value=<?php echo $_REQUEST['teacher_id'];?>>       
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
	<?php 
}
else 
{?>
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="active">
          <a href="#teacher" role="tab" data-toggle="tab">
             <i class="fa fa-align-justify"></i> <?php _e('Teacher List', 'school-mgt'); ?></a>
          </a>
      </li>
</ul>
<script>
jQuery(document).ready(function() {
	
    jQuery('#teacher_list1').DataTable({
        responsive: true
    });
} );
</script>
<div class="tab-content">
      
        	<div class="panel-body">
		<div class="table-responsive">
        <table id="teacher_list1" class="display dataTable" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th width="75px"><?php echo _e( 'Photo', 'school-mgt' ) ;?></th>
                <th><?php echo _e( 'Teacher Name', 'school-mgt' ) ;?></th>
                <th> <?php echo _e( 'Teacher Email', 'school-mgt' ) ;?></th>
                <?php if($school_obj->role == 'teacher'){?>
                <th> <?php echo _e( 'Action', 'school-mgt' ) ;?></th>
                <?php }?>
            </tr>
        </thead>
		<tfoot>
            <tr>
               <th><?php echo _e( 'Photo', 'school-mgt' ) ;?></th>
                <th><?php echo _e( 'Teacher Name', 'school-mgt' ) ;?></th>
                <th> <?php echo _e( 'Teacher Email', 'school-mgt' ) ;?></th>
                <?php if($school_obj->role == 'teacher'){?>
                <th> <?php echo _e( 'Action', 'school-mgt' ) ;?></th>
                <?php }?>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		 $teacherdata=get_usersdata('teacher');
		 if(!empty($teacherdata))
			{
		 	foreach ($teacherdata as $retrieved_data){ 
			 ?>
            <tr>
				<td class="user_image text-center"><?php $uid=$retrieved_data->ID;
							$umetadata=get_user_image($uid);
							if(empty($umetadata['meta_value']))
							echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle" />';
							else
							echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';
				?></td>
                <td class="name"><?php echo $retrieved_data->display_name;?></td>
                <td class="email"><?php echo $retrieved_data->user_email;?></td>
				 <?php if($school_obj->role == 'teacher'){?>
               <td>
               <?php if($retrieved_data->ID == get_current_user_id())
               			{?>
               <a href="?dashboard=user&page=teacher&teacher_id=<?php echo $retrieved_data->ID;?>&attendance=1" class="btn btn-default"  
                                    idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php _e('View Attendance','school-mgt');?> </a>
                                    <?php }?>
               </td>
				 <?php } ?>
            </tr>
            <?php }

			} ?>
     
        </tbody>
        
        </table>
		</div>
		</div>
	</div>
</div>
<?php } ?>     