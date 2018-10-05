<?php 
	//This is Dashboard at admin side
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#expense_form').validationEngine();
	$('#invoice_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        dateFormat: 'yy-mm-dd',
	        yearRange:'-65:+25',
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
                    
                }); 
		
} );
</script>
	
     <?php 	

	if($active_tab == 'addpaymentfee')
	 {
        	$fees_pay_id=0;
			if(isset($_REQUEST['fees_pay_id']))
				$fees_pay_id=$_REQUEST['fees_pay_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_feespayment->smgt_get_single_fee_payment($fees_pay_id);
					//var_dump($result);
				
				}?>
		
       <div class="panel-body">
        <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="fees_pay_id" value="<?php echo $fees_pay_id;?>">
		<input type="hidden" name="invoice_type" value="expense">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="class_id"><?php _e('Class','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php
				if($edit){ $classval=$result->class_id; }else{$classval='';}?>
                     <select name="class_id" id="class_list" class="form-control validate[required] load_fees">
                     <?php if($addparent){ 
					 		$classdata=get_class_by_id($student->class_name);
						?>
                     <option value="<?php echo $student->class_name;?>" ><?php echo $classdata->class_name;?></option>
                     <?php }?>
                    	<option value=""><?php _e('Select Class','school-mgt');?></option>
                            <?php
								foreach(get_allclass() as $classdata)
								{ ?>
								 <option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
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
		<?php if($edit){ ?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="student_list"><?php _e('Student','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
                     
                     <select name="student_id" id="student_list" class="form-control validate[required]">
                      	<option value=""><?php _e('Select student','school-mgt');?></option>
						<?php 
							if($edit)
							{
				echo '<option value="'.$result->student_id.'" '.selected($result->student_id,$result->student_id).'>'.get_user_name_byid($result->student_id).'</option>';
							}
						?>
                     </select>
			</div>
		</div>
		<?php }
		else{
			?>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="student_list"><?php _e('Student','school-mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
                     
                     <select name="student_id" id="student_list" class="form-control">
                      	<option value=""><?php _e('Select Student','school-mgt');?></option>
						<?php 
							if($edit)
							{
				echo '<option value="'.$result->student_id.'" '.selected($result->student_id,$result->student_id).'>'.get_user_name_byid($result->student_id).'</option>';
							}
						?>
                     </select>
				<p><?php _e('If You want to generate invoice for single student then select student othere wise generate invoice for all student as selected class','school-mgt');?></p>
			</div>
		</div>
			<?php
		}
		?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="category_data"><?php _e('Fee Type','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="fees_id" id="fees_data" class="form-control validate[required]">
					<option value = ""><?php _e('Select Fee Type','school-mgt');?></option>
				<?php 				
				if($edit)
				{
				echo '<option value="'.$result->fees_id.'" '.selected($result->fees_id,$result->fees_id).'>'.get_fees_term_name($result->fees_id).'</option>';
				}
				?>
			</select>
			</div>
			
		</div>
		
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="fees_amount"><?php _e('Amount','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="fees_amount" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $result->total_amount;}elseif(isset($_POST['fees_amount'])) echo $_POST['fees_amount'];?>" name="fees_amount" readonly>
			</div>
		</div>
		<!-- <div class="form-group">
			<label class="col-sm-2 control-label" for="fees_paid_amount"><?php _e('Paid Amount','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="fees_paid_amount" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $result->fees_paid_amount;}elseif(isset($_POST['fees_paid_amount'])) echo $_POST['fees_paid_amount'];?>" name="fees_paid_amount">
			</div>
		</div> -->
		<div class="form-group">
			<label class="col-sm-2 control-label" for="description"><?php _e('Description','school-mgt');?></label>
			<div class="col-sm-8">
				<textarea name="description" class="form-control"> <?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?> </textarea>
				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="start_year"><?php _e('Year','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-4">
				 <select name="start_year" id="start_year" class="form-control validate[required]">
                      	<option value=""><?php _e('Starting year','school-mgt');?></option>
						<?php 
						$start_year = 0;
						if($edit)
							$start_year = $result->start_year;
							for($i=2000 ;$i<2030;$i++)
							{
								echo '<option value="'.$i.'" '.selected($start_year,$i).'>'.$i.'</option>';
							}
						?>
					</select>
			</div>
			<div class="col-sm-4">
				 <select name="end_year" id="end_year" class="form-control validate[required]">
                      	<option value=""><?php _e('Ending year','school-mgt');?></option>
						<?php 
						$end_year = '';
						if($edit)
							$end_year = $result->end_year;
							for($i=00 ;$i<31;$i++)
							{
								echo '<option value="'.$i.'" '.selected($end_year,$i).'>'.$i.'</option>';
							}
						?>
					</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="smgt_enable_feesalert_mail"><?php _e('Enable Send  Mail To Parents','school-mgt');?></label>
			<div class="col-sm-8">
				<div class="checkbox">
					<label>
	              		<input type="checkbox" name="smgt_enable_feesalert_mail"  value="1" <?php echo checked(get_option('smgt_enable_feesalert_mail'),'yes');?>/><?php _e('Enable','school-mgt');?>
	              </label>
              </div>
			</div>
		</div>
		
		<div class="col-sm-offset-2 col-sm-8">
        	 <input type="submit" value="<?php if($edit){ _e('Save Invoice','school-mgt'); }else{ _e('Create Invoice','school-mgt');}?>" name="save_feetype_payment" class="btn btn-success"/>
        </div>
        </form>
        </div>
       <script>

   
   
   	
  
   	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	$(document).ready(function() { 
   		blank_expense_entry = $('#expense_entry').html();
   		//alert("hello" + blank_invoice_entry);
   	}); 

   	function add_entry()
   	{
   		$("#expense_entry").append(blank_expense_entry);
   		//alert("hellooo");
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n){
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
       </script> 
     <?php 
	 }
	 ?>