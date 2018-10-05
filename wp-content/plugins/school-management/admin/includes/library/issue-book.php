<?php $obj_lib = new Smgtlibrary();	?>
<script type="text/javascript">
//$.noConflict();
$(document).ready(function() {
	$('#book_form').validationEngine();
	  $('.datepicker').datepicker();
	 // $('#book_list').multiselect();
});
</script>
			<?php $issuebook_id=0;
				if(isset($_REQUEST['issuebook_id']))
					$issuebook_id=$_REQUEST['issuebook_id'];
				$edit=0;
			 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				 $edit=1;
				 $result=$obj_lib->get_single_issuebooks($issuebook_id);
				 
			}?>
       
        <div class="panel-body">	
        <form name="book_form" action="" method="post" class="form-horizontal" id="book_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="issue_id" value="<?php echo $issuebook_id;?>">
        
		<div class="form-group">
			<label class="col-sm-2 control-label" for="class_id"><?php _e('Class','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
				<select name="class_id" id="class_list" class="form-control validate[required]">
                    <option value=""><?php _e('Select Class','school-mgt');?></option>
                            <?php
								
								foreach(get_allclass() as $classdata)
								{ ?>
								 <option value="<?php echo $classdata['class_id'];?>" <?php selected($classval,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>
								
						   
						   <?php }?>
                     </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="class_name"><?php _e('Class Section','school-mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
                        <select name="class_section" class="form-control" id="class_section">
                        	<option value=""><?php _e('Select Class Section','school-mgt');?></option>
                            <?php
							if($edit){
								foreach(smgt_get_class_sections($result->class_id) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							}?>
                        </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="student_list"><?php _e('Student','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				
                     
                     <select name="student_id" id="student_list" class="form-control validate[required]">
                    
                     <?php if(isset($result->student_id)){ 
						$student=get_userdata($result->student_id);
					?>
                     <option value="<?php echo $result->student_id;?>" ><?php echo $student->first_name." ".$student->last_name;?></option>
                     <?php }
							else
							{?>
                    	<option value=""><?php _e('Select student','school-mgt');?></option>
							<?php } ?>
                     </select>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="issue_date"><?php _e('Issue Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="issue_date" class="datepicker form-control validate[required] text-input" type="text" name="issue_date" value="<?php if($edit){ echo smgt_getdate_in_input_box($result->issue_date);}else{echo date('m/d/Y');}?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="period"><?php _e('Period','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="period_id" id="issue_period" class="form-control validate[required]">
					<option value = ""><?php _e('Select Period','school-mgt');?></option>
					<?php 
					if($edit)
						$period_id = $result->period;
						$category_data = $obj_lib->smgt_get_periodlist();
				
					if(!empty($category_data))
					{
						foreach ($category_data as $retrieved_data)
						{
							echo '<option value="'.$retrieved_data->ID.'" '.selected($period_id,$retrieved_data->ID).'>'.$retrieved_data->post_title.' Days</option>';
						}
					}
					?>
			</select>
			</div>
			<div class="col-sm-2">
				<button id="addremove" model="period_type"><?php _e('Add Or Remove','school-mgt');?></button>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="return_date"><?php _e('Return Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="return_date" class="form-control validate[required] text-input" type="text" name="return_date" value="<?php if($edit){ echo smgt_getdate_in_input_box($result->end_date);}?>">
			</div>
		</div>
		
			<div class="form-group">
			<label class="col-sm-2 control-label" for="category_data validate[required]"><?php _e('Book Category','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="bookcat_id" id="bookcat_list" class="form-control">
					<option value = ""><?php _e('Select Category','school-mgt');?></option>
				<?php if($edit)
						$book_cat = $result->cat_id;
						$category_data = $obj_lib->smgt_get_bookcat();
				
					if(!empty($category_data))
					{
						foreach ($category_data as $retrieved_data)
						{
							echo '<option value="'.$retrieved_data->ID.'" '.selected($book_cat,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';
						}
					}
					?>
			</select>
			</div>
			
			
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="book_name"><?php _e('Book Name','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>
			<div class="col-sm-8">
				<?php if($edit){ $book_id=$result->book_id; }else{$book_id=0;}?>
                     <select name="book_id[]" id="book_list" multiple="multiple" class="form-control validate[required]">
					 <option value = ""><?php _e('Select Book','school-mgt');?></option>
                     <?php $books_data=$obj_lib->get_all_books(); 
					 foreach($books_data as $book){?>
						  <option value="<?php echo $book->id;?>" <?php selected($book_id,$book->id);?>><?php echo stripslashes($book->book_name);?></option>
					 <?php } ?>
                     </select>
			
			</div>
			</div>
			
		
		<div class="col-sm-offset-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ _e('Save Issue Book','school-mgt'); }else{ _e('Issue Book','school-mgt');}?>" name="save_issue_book" class="btn btn-success" />
        </div>
        
        </form>
        </div>
  
<?php

?>