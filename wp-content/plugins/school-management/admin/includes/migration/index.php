<?php 
	// This is Class at admin side!!!!!!!!! 
if(isset($_REQUEST['migration']))
{
	$current_class = $_REQUEST['current_class'];
	$next_class = $_REQUEST['next_class'];
	$exam_id = $_REQUEST['exam_id'];
	$passing_marks = $_REQUEST['passing_marks'];
	
	
	$student_fail = fail_student_list($current_class,$next_class,$exam_id,$passing_marks);
	$update = smgt_migration($current_class,$next_class,$exam_id,$student_fail);
	
	?>
	<div id="message" class="updated below-h2">
								<p><?php _e('Migration successfully.','school-mgt');?></p>
							</div>
	<?php 
	
}	
	?>

<script type="text/javascript">

$(document).ready(function() {
	 $('#select_data').validationEngine();
} );

</script>

<div class="page-inner" style="min-height:1631px !important">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div  id="main-wrapper" class="marks_list">
	<div class="panel panel-white">
	<div class="panel-body">  
 
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_Migration" class="nav-tab  nav-tab-active">
		<?php echo '<span class="dashicons dashicons-awards"></span>'.__('Migration', 'school-mgt'); ?></a>
        
    </h2>
    <?php
	$tablename="marks";
	?>	 
	<div class="panel-body"> 
    <form method="post" id="select_data">  
   
    <div class="form-group col-md-3">
    	<label for="current_class"><?php _e('Select Current Class','school-mgt');?><span class="require-field">*</span></label>
       <select name="current_class"  id="current_class" class="form-control validate[required] text-input">
                	<option value=" "><?php _e('Select Current Class Name','school-mgt');?></option>
                    <?php
					  foreach(get_allclass() as $classdata)
					  {  
					  ?>
					   <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
				 <?php }?>
                </select>
    </div>
    <div class="form-group col-md-3">
    	<label for="next_class"><?php _e('Select Next Class','school-mgt');?><span class="require-field">*</span></label>
       <select name="next_class"  id="next_class" class="form-control validate[required] text-input">
                	<option value=" "><?php _e('Select Class Name','school-mgt');?></option>
                    <?php
					  foreach(get_allclass() as $classdata)
					  {  
					  ?>
					   <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
				 <?php }?>
                </select>
    </div>
     <div class="form-group col-md-3">
    	<label for="exam_id"><?php _e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
        <?php
					$tablename="exam"; 
					$retrieve_class = get_all_data($tablename);
				
					?>
            	<select name="exam_id" class="form-control validate[required] text-input">
                	<option value=" "><?php _e('Select Exam Name','school-mgt');?></option>
                    <?php
					foreach($retrieve_class as $retrieved_data)
					{
						
					?>
                    <option value="<?php echo $retrieved_data->exam_id;?>"><?php echo $retrieved_data->exam_name;?></option>
					<?php	
					}
					?>
                </select>
    </div>
    <div class="form-group col-md-2">
    	<label for="next_class"><?php _e('Passing Marks','school-mgt');?><span class="require-field">*</span></label>
       <input type="text" name="passing_marks" value=""  class="form-control validate[required]">
    </div>
    <div class="form-group col-md-1 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" value="<?php _e('Go','school-mgt');?>" name="migration"  class="btn btn-info"/>
    </div>
   
      </form>
	  </div>
      <div class="clearfix"> </div>
    
	 
   
	 </div>
	 </div>
	 </div>    
</div>
<?php ?>