<?php
	

?>
<script type="text/javascript">

$(document).ready(function() {
	 $('#exam_form').validationEngine();
	 $('.datepicker').datepicker();

} );
</script>

        	 	<?php  $edit=0;
			 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						 
						 $edit=1;
						 $exam_data= get_exam_by_id($_REQUEST['exam_id']);
					}
					?>
        <div class="panel-body">	
        <form name="exam_form" action="" method="post" class="form-horizontal" id="exam_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        <div class="form-group">
			<label class="col-sm-2 control-label " for="exam_name"><?php _e('Exam Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="exam_name" class="form-control validate[required]" type="text" value="<?php if($edit){ echo $exam_data->exam_name;}?>" name="exam_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="exam_date"><?php _e('Exam Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="exam_date" class="datepicker form-control validate[required] text-input" type="text" name="exam_date" value="<?php if($edit){ echo smgt_getdate_in_input_box($exam_data->exam_date);}?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="exam_comment"><?php _e('Exam Comment','school-mgt');?></label>
			<div class="col-sm-8">
			 <textarea name="exam_comment" class="form-control" id="exam_comment"><?php if($edit){ echo $exam_data->exam_comment;}?></textarea>
				
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ _e('Save Exam','school-mgt'); }else{ _e('Add Exam','school-mgt');}?>" name="save_exam" class="btn btn-success" />
        </div>
        
        </form>
        </div>
       
<?php

?>