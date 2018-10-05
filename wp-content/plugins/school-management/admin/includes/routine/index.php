<?php 
$obj_route = new Class_routine();	
if(isset($_POST['save_route']))
{
	$teacherid = get_teacherid_by_subjectid($_POST['subject_id']);
	$route_data=array('subject_id'=>$_POST['subject_id'],
			'class_id'=>$_POST['class_id'],
			'section_name'=>$_POST['class_section'],
			'teacher_id'=>$teacherid,
			'start_time'=>$_POST['start_time'].':'.$_POST['start_min'].':'.$_POST['start_ampm'],
			'end_time'=>$_POST['end_time'].':'.$_POST['end_min'].':'.$_POST['end_ampm'],
			'weekday'=>$_POST['weekday']
	);


	if($_REQUEST['action']=='edit')
	{
		$route_id=array('route_id'=>$_REQUEST['route_id']);

		$obj_route->update_route($route_data,$route_id);

		$message="Update Route Successfully";
	}
	else
	{
		$retuen_val = $obj_route->is_route_exist($route_data);
	
		if($retuen_val == 'success')
		{
			$obj_route->save_route($route_data);
			$message= __('Add Route Successfully','school-mgt');
		}
		elseif($retuen_val == 'duplicate')
		{		
			$message = __('Dublicate Record','school-mgt');	
		}
		elseif($retuen_val == 'teacher_duplicate')
		{
			$message = __('Teacher is not available','school-mgt');
		}
			
	}


}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$tablenm = "smgt_time_table";
	$result=delete_route($tablenm,$_REQUEST['route_id']);
	if($result)
	{
		$message = __("Record Successfully Deleted.","school-mgt");
	}
}
if(isset($message)){
?>
<div id="message" class="updated below-h2">
					<p><?php  echo $message; ?></p>
				</div>
<?php }?>				
<div class="popup-bg">
    <div class="overlay-content">
    <a href="#" class="close-btn">X</a>
     <div class="edit_perent"></div>
      <div class="view-parent"></div>
     <a href="#" class="close-btn"><?php _e('Close','school-mgt');?></a>
    </div> 
    
</div>
<?php 
	// This is Class at admin side!!!!!!!!! 

	?>
	<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'route_list';
	?>
<div class="page-inner" style="min-height:1600px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div id="main-wrapper" class="grade_page">
<div class="panel panel-white">
<div class="panel-body">
<div class=" class_list">  
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_route&tab=route_list" class="nav-tab <?php echo $active_tab == 'route_list' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Route List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{ ?>
       <a href="?page=smgt_route&tab=addroute&action=edit&route_id=<?php echo $_REQUEST['route_id'];?>" class="nav-tab <?php echo $active_tab == 'addroute' ? 'nav-tab-active' : ''; ?>">
		<?php _e('Edit Route', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_route&tab=addroute" class="nav-tab <?php echo $active_tab == 'addroute' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Route', 'school-mgt'); ?></a>  
        <?php } ?>
        <a href="?page=smgt_route&tab=teacher_timetable" class="nav-tab <?php echo $active_tab == 'teacher_timetable' ? 'nav-tab-active' : ''; ?>">
        <?php echo '<span class="dashicons dashicons-calendar-alt"></span>'.__('Teacher Time Table','school-mgt');?>
        </a>
    </h2>
    <?php
	
	if($active_tab == 'route_list')
	{	
	?>	
     <div class="panel panel-white">
        	
         
         <div class="panel-body">
         <div id="accordion" class="panel-group" aria-multiselectable="true" role="tablist">
         <?php 
		$retrieve_class = get_all_data('smgt_class');
		$i=0;
		foreach($retrieve_class as $class)
		{ 
			$sectionname="";
			$sectionid="";
			$class_sectionsdata=smgt_get_class_sections($class->class_id);
			if(!empty($class_sectionsdata))
			{
				foreach($class_sectionsdata as $section)
				{ 
				  $i++;
					$sectionname=$section->section_name;
					$sectionid=$section->id;
				?>
				<div class="panel panel-default">
				<div id="heading_<?php echo $i;?>" class="panel-heading" role="tab">
				<h4 class="panel-title">
				<a class="collapsed" aria-controls="collapse_<?php echo $i;?>" aria-expanded="false" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
				<?php _e('Class', 'school-mgt'); ?> <?php echo $class->class_name." ".$section->section_name; ?> </a>
				
				</h4>
			   </div>
			   <div id="collapse_<?php echo $i;?>" class="panel-collapse collapse" aria-labelledby="heading_<?php echo $i;?>" role="tabpanel" aria-expanded="false" style="height: 0px;">
			   <div class="panel-body">
			   <table class="table table-bordered">
			<?php
			
			foreach(sgmt_day_list() as $daykey => $dayname)
			{ ?>
			<tr>
			 <th width="100"><?php echo $dayname;?></th>
			<td>
				 <?php
					$period = $obj_route->get_periad($class->class_id,$section->id,$daykey);
					if(!empty($period))
						foreach($period as $period_data)
						{
							echo '<div class="btn-group m-b-sm">';
							echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.get_single_subject_name($period_data->subject_id);
							echo '<span class="time"> ('.$period_data->start_time.'- '.$period_data->end_time.') </span>';
							echo '</span><span class="caret"></span></button>';
							echo '<ul role="menu" class="dropdown-menu">
													<li><a href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'.__('Edit','school-mgt').'</a></li>
													<li><a href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'">'.__('Delete','school-mgt').'</a></li>
													
												</ul>';
							echo '</div>';
							
						}
				 ?>
			</td>
			</tr>
			<?php } ?>
			</table> 
			</div>
			</div>
			</div>
		   
			<?php
			} 
			
		}
		else
		{ ?>
				<div class="panel panel-default">
				
			   <div id="heading_<?php echo $i;?>" class="panel-heading" role="tab">
				<h4 class="panel-title">
				<a class="collapsed" aria-controls="collapse_<?php echo $i;?>" aria-expanded="false" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
				<?php _e('Class', 'school-mgt'); ?> <?php echo $class->class_name; ?> </a>
				
				</h4>
			   </div>
			   <div id="collapse_<?php echo $i;?>" class="panel-collapse collapse" aria-labelledby="heading_<?php echo $i;?>" role="tabpanel" aria-expanded="false" style="height: 0px;">
			   <div class="panel-body">
			   <table class="table table-bordered">
			<?php
			
			$sectionid=0;
			foreach(sgmt_day_list() as $daykey => $dayname)
			{ ?>
			<tr>
			 <th width="100"><?php echo $dayname;?></th>
			<td>
				 <?php
					
					$period = $obj_route->get_periad($class->class_id,$sectionid,$daykey);
					if(!empty($period))
						foreach($period as $period_data)
						{
							echo '<div class="btn-group m-b-sm">';
							echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.get_single_subject_name($period_data->subject_id);
							echo '<span class="time"> ('.$period_data->start_time.'- '.$period_data->end_time.') </span>';
							echo '</span><span class="caret"></span></button>';
							echo '<ul role="menu" class="dropdown-menu">
													<li><a href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'.__('Edit','school-mgt').'</a></li>
													<li><a href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'">'.__('Delete','school-mgt').'</a></li>
													
												</ul>';
							echo '</div>';
							
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
			
		<?php }
		$i++; 
		}
		?>
		</div>
        </div>
        </div>
       
     <?php 
		
	 }
	if($active_tab == 'addroute')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/routine/add-route.php';
		
	 }
	 if($active_tab == 'teacher_timetable')
	 {
		?>
		 <div class="panel panel-white">
        	<div class="panel-heading clearfix">
<h3 class="panel-title"><?php _e('Teacher Time Table','school-mgt');?></h3>
</div>
		 <div class="panel-body">
		 <div id="accordion" class="panel-group" aria-multiselectable="true" role="tablist">
        <?php 
		
		$teacherdata=get_usersdata('teacher');
		if(!empty($teacherdata))
		{	
			$i=0;
		foreach($teacherdata as $retrieved_data)
		{
		?>
        	<div class="panel panel-default">
        	<div id="heading_<?php echo $i;?>" class="panel-heading" role="tab">
           	<h4 class="panel-title">
           	<a class="collapsed" aria-controls="collapse_<?php echo $i;?>" aria-expanded="false" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
           	<?php _e('Teacher','school-mgt');?>: <?php echo $retrieved_data->display_name;?> </a>
           	
           	</h4>
           </div>
			
            <div id="collapse_<?php echo $i;?>" class="panel-collapse collapse" aria-labelledby="heading_<?php echo $i;?>" role="tabpanel" aria-expanded="false" style="height: 0px;">
             <div class="panel-body">
            <table class="table table-bordered">
        <?php 
        $i++;
		foreach(sgmt_day_list() as $daykey => $dayname)
		{
		?>
		<tr>
       <th width="100"><?php echo $dayname;?></th>
        <td>
        	 <?php
			 	$period = $obj_route->get_periad_by_teacher($retrieved_data->ID,$daykey);
				if(!empty($period))
					foreach($period as $period_data)
					{
						echo '<div class="btn-group m-b-sm">';
						echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.get_single_subject_name($period_data->subject_id);
						echo '<span class="time"> ('.$period_data->start_time.'- '.$period_data->end_time.') </span>';
						echo '<span>'.get_class_name($period_data->class_id).'</span>';
						echo '</span></span><span class="caret"></span></button>';
						echo '<ul role="menu" class="dropdown-menu">
                                                <li><a href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'.__('Edit','school-mgt').'</a></li>
                                                <li><a href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'">'.__('Delete','school-mgt').'</a></li>
						
                                            </ul>';
						echo '</div>';
						
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
         
		<?php }
		} ?>
		</div>
		</div>
		<?php
		
	 } ?>
	
</div>
</div>
</div>
</div>
</div>
<?php ?>