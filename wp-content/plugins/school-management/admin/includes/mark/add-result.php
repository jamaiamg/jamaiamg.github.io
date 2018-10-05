<?php
if(isset($_POST['save_exam']))
{
	
	$created_date = date("Y-m-d H:i:s");
	$examdata=array('exam_name'=>$_POST['exam_name'],
					'exam_date'=>$_POST['exam_date'],
					'exam_comment'=>$_POST['exam_comment'],					
					'exam_creater_id'=>get_current_user_id(),
					'created_date'=>$created_date
					
	);
	//table name without prefix
	$tablename="exam";
	
	if($_REQUEST['action']=='edit')
	{
		$grade_id=array('exam_id'=>$_REQUEST['exam_id']);
		$modified_date_date = date("Y-m-d H:i:s");
		$examdata['modified_date']=$modified_date_date;
		update_record($tablename,$examdata,$grade_id);
		$message=__('Update Exam Successfully','school-mgt');
	}
	else
	{
		$reult=insert_record($tablename,$examdata);
		
			$message=__('Add Exam Successfully','school-mgt');
	}
	
}

	

?>	

		<div class="add_class">
		<h2>
        	 	<?php  $edit=0;
			 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{
						 echo esc_html( __( 'Edit Exam', 'school-mgt') );
						 $edit=1;
						 $exam_data= get_exam_by_id($_REQUEST['exam_id']);
					}
					else
					{
							 echo esc_html( __( 'Add New Exam', 'school-mgt') );
					}?>
        </h2>
        <?php
		if(isset($message))
			echo '<div id="message" class="updated below-h2"><p>'.$message.'</p></div>';
		?>
        <form name="class_form" action="" method="post" id="marks_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        
        <table class="form-table">	
        		<tr>
                    <th>
                        <label><?php _e('Exam Name','school-mgt');?> <span class="require-field">*</span></label></th>
                    <td>
                         <input type="text" name="exam_name"  class="regular-text" value="<?php if($edit){ echo $exam_data->exam_name;}?>"/> 
                    </td>
        		</tr>
                <tr>	
                    <th >
                        <label><?php _e('Exam Date','school-mgt');?>  </label></th>
                    <td>
                        <input type="date" name="exam_date" value="<?php if($edit){ echo $exam_data->exam_date;}?>"/>
                    </td>
        		</tr>
                <tr>
                    <th >
                        <label><?php _e('Exam Comment','school-mgt');?>  </label></th>
                    <td>
                       <textarea name="exam_comment"><?php if($edit){ echo $exam_data->exam_comment;}?></textarea>
                    </td>
        		</tr>             
                <tr>
                	<th ></th>
                	<td><input type="submit" value="<?php if($edit){ _e('Save Exam','school-mgt'); }else{ _e('Add Exam','school-mgt');}?>" name="save_exam"/></td>
                </tr>
                
                
        </table>      	
        
        </form>
        
        </div>
<?php

?>