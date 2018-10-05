<?php 
//Subject
//var_dump($school_obj->subject);
if($school_obj->role == 'student')
{
	$subjects = $school_obj->subject;
	
}
else
$subjects = get_all_data('subject');
?>
<script type="text/javascript">

$(document).ready(function() {
	 $('#add_subject_form').validationEngine();
	 
} );

jQuery(document).ready(function() {
	jQuery('#subject_list').DataTable({
        responsive: true
    });
} );

</script>
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="active">
          <a href="#subject" role="tab" data-toggle="tab">
             <i class="fa fa-align-justify"></i> <?php _e('Subject List', 'school-mgt'); ?></a>
          </a>
      </li>
      <?php if($school_obj->role == 'teacher'){?>
      <li><a href="#add_subject" role="tab" data-toggle="tab">
        <i class="fa fa-plus-circle"></i> <?php _e('Add Subject', 'school-mgt'); ?></a> 
      </li>
     <?php }?>
    </ul>
    
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane fade active in" id="subject">
         <div class="panel-body">
        <div class="table-responsive">
        <table id="subject_list" class="display dataTable" cellspacing="0" width="100%">
        	 <thead>
            <tr>                
                <th> <?php _e('Class', 'school-mgt');?></th>
                <th> <?php _e('Section', 'school-mgt');?></th>
                <th><?php _e('Subject Name', 'school-mgt');?></th>
                <th><?php _e('Teacher Name', 'school-mgt');?></th>  
				<th><?php _e('Action', 'school-mgt');?></th>   				
            </tr>
        </thead>
		<tfoot>
            <tr>
               <th><?php _e('Class', 'school-mgt');?></th>
			      <th> <?php _e('Section', 'school-mgt');?></th>
                <th><?php _e('Subject Name', 'school-mgt');?></th>
                <th><?php _e('Teacher Name', 'school-mgt');?></th>
				<th><?php _e('Action', 'school-mgt');?></th>
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
          if($school_obj->role !='parent')
          {
		 	foreach ($subjects as $retrieved_data){ 
			
		 ?>
            <tr>
                <td><?php echo get_class_name($retrieved_data->class_id);?></td>
				<td><?php if($retrieved_data->section_id!=""){ echo  smgt_get_section_name($retrieved_data->section_id); }else { _e('No Section','school-mgt');}?></td>
                <td><?php echo $retrieved_data->sub_name;?></td>
                <td><?php echo get_user_name_byid($retrieved_data->teacher_id);?></td>              
               <td>
			   <?php if($retrieved_data->syllabus!=''){?>
				   
				   <a href="<?php echo content_url().'/uploads/school_assets/'.$retrieved_data->syllabus;?>" class="btn btn-default"><i class="fa fa-download"></i><?php _e('Syllabus','school-mgt');?></a>
			   <?php }
					else{?>
						<a href="#" class="btn btn-default"><i class="fa fa-download"></i><?php _e('Syllabus','school-mgt');?></a>
						
					<?php }
			   ?>
			   </td>
            </tr> 
            <?php } 
          }
          else 
          {
          	$chid_array =$school_obj->child_list;
          	foreach ($chid_array as $child_id)
          	{
          		$class_info = $school_obj->get_user_class_id($child_id);
          		$subjects = $school_obj->subject_list($class_info->class_id);
          		
          	foreach ($subjects as $retrieved_data){
          		
          		?>
          	            <tr>
          	                <td><?php echo get_class_name($retrieved_data->class_id);?></td>
          	                <td><?php echo $retrieved_data->sub_name;?></td>
          	                <td><?php echo get_user_name_byid($retrieved_data->teacher_id);?></td>  
							<td><a href="<?php echo content_url().'/uploads/school_assets/'.$retrieved_data->syllabus;?>" class="btn btn-default"><i class="fa fa-download"></i><?php _e('Syllabus','school-mgt');?></a></td>
          	               
          	            </tr> 
          	            <?php } }
          }
            ?>
     
        </tbody>
        
        </table>
          </div>
		  </div>
      </div>
      <div class="tab-pane fade" id="add_subject">
         
 <?php
 			
			if(isset($_POST['subject']))
			{
				if(isset($_FILES['subject_syllabus']))
				{
					
					
					
					 echo $syllabus=inventory_image_upload($_FILES['subject_syllabus']);
					
					
					
		
				}
				else
				{
					 $syllabus='syllabus.pdf';
				}
				$subjects=array('sub_name'=>$_POST['subject_name'],
								'class_id'=>$_POST['subject_class'],
								'section_id'=>$_POST['class_section'],
								'teacher_id'=>$_POST['subject_teacher'],
								'edition'=>$_POST['subject_edition'],
								'author_name'=>$_POST['subject_author'],	
								'syllabus'=>$syllabus
				);
				$tablename="subject";
				if($_REQUEST['action']=='edit')
				{
						$subid=array('subid'=>$_REQUEST['subject_id']);
						update_record($tablename,$subjects,$subid);
				}
				else
				{
					$result=insert_record($tablename,$subjects);
					
				}
				wp_safe_redirect(home_url()."?dashboard=user&page=subject" );
				exit();
				
			
			}
			
			?>
			
						<?php 	$edit=0;
								if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
								{	
									$edit=1;
									
									$subject=get_subject($_REQUEST['subject_id']);
								}
								?>
					
<div class="panel-body">	
<form name="student_form" action="" method="post" class="form-horizontal" id="add_subject_form" enctype="multipart/form-data">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="subject_name"><?php _e('Subject Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="subject_name" class="form-control validate[required]" type="text" value="<?php if($edit){ echo $subject->sub_name;}?>" name="subject_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="subject_class"><?php _e('Class','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $classval=$subject->class_id; }else{$classval='';}?>
                        <select name="subject_class" class="form-control validate[required]" id="class_list">
                        	<option value=""><?php _e('Select Class', 'school-mgt');?></option>
                            <?php
								foreach(get_allclass() as $classdata)
								{ ?>
								 <option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
							<?php } ?>
                    </select>
            </div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="class_name"><?php _e('Class Section','school-mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $sectionval=$subject->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
                        <select name="class_section" class="form-control" id="class_section">
                        	<option value=""><?php _e('Select Class Section','school-mgt');?></option>
                            <?php
							if($edit){
								foreach(smgt_get_class_sections($subject->class_id) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							}?>
                        </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="subject_teacher"><?php _e('Teacher','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $teachval=$subject->teacher_id; }else{$teachval='';}?>
                        <select name="subject_teacher" id="class_teacher" class="form-control validate[required]">
                        	<option value=""><?php _e('Select Teacher', 'school-mgt');?></option>
                           <?php 
                            	foreach(get_usersdata('teacher') as $teacherdata)
								{ ?>
								 <option value="<?php echo $teacherdata->ID;?>" <?php selected($teachval, $teacherdata->ID);  ?>><?php echo $teacherdata->display_name;?></option>
							<?php }?>
                        </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="subject_edition"><?php _e('Edition','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="subject_edition" class="form-control" type="text" value="<?php if($edit){ echo $subject->edition;}?>" name="subject_edition">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="subject_author"><?php _e('Author Name','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="subject_author" class="form-control" type="text" value="<?php if($edit){ echo $subject->author_name;}?>" name="subject_author">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="subject_syllabus"><?php _e('Syllabus','school-mgt');?></label>
			<div class="col-sm-8">
				 <input type="file" name="subject_syllabus"  id="subject_syllabus" class="file_upload"/> 
                        
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label"></label>
        	<div class="col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Subject','school-mgt'); }else{ _e('Add Subject','school-mgt');}?>" name="subject" class="btn btn-success"/>
			</div>
        </div>
        </form>
		</div>
			<?php

			?>
      </div>
     
    </div>
</div>
<?php ?>