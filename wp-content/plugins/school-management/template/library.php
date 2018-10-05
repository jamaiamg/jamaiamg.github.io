<?php 

	$obj_lib= new Smgtlibrary();
	//--------------Delete code-------------------------------
	
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			
			$result=$obj_lib->delete_book($_REQUEST['book_id']);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('record successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
		}
		if(isset($_REQUEST['delete_selected_book']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=$obj_lib->delete_book($id);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete' && $_REQUEST['tab']=='issuelist' && isset($_REQUEST['issuebook_id']))
		{
			
			$result=$obj_lib->delete_issuebook($_REQUEST['issuebook_id']);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('record successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
		}
		if(isset($_REQUEST['delete_selected_issuebook']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=$obj_lib->delete_issuebook($id);
		if($result)
			{?>
				<div id="message" class="updated below-h2">
					<p><?php _e('Record Successfully Delete!','school-mgt');?></p>
				</div>
		<?php 
			}
	}
	//------------------Edit-Add code ------------------------------
if(isset($_POST['save_book']))
{
	
	
	if($_REQUEST['action']=='edit')
	{
			
			$result=$obj_lib->add_book($_POST);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Record Successfully Updated!','school-mgt');?></p>
					</div>
	  <?php }
			
	}
	else
	{
			$result=$obj_lib->add_book($_POST);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Record Successfully Inserted!','school-mgt');?></p>
					</div>
	  <?php }
				
	}
	
	
	
}
if(isset($_POST['save_issue_book']))
{
	
	if($_REQUEST['action']=='edit')
	{
			
			$result=$obj_lib->add_issue_book($_POST);
			if($result)
			{ ?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Record Successfully Updated!','school-mgt');?></p>
					</div>
	  <?php }
			
	}
	else
	{
			$result=$obj_lib->add_issue_book($_POST);
			if($result)
			{?>
				<div id="message" class="updated below-h2">
						<p><?php _e('Record Successfully Inserted!','school-mgt');?></p>
					</div>
	  <?php }
				
	}
}
if(isset($_POST['submit_book']))
{
	//var_dump($_POST);
	
	$result=$obj_lib->submit_return_book($_POST);
	if($result)
	{ ?>
		<div id="message" class="updated below-h2">
			<p><?php _e('Book Submitted Successfully','school-mgt');?></p>
		</div>
	<?php }
			
}	
	?>
	<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'memberlist';
	?>
<script type="text/javascript">
$(document).ready(function() {
	 $('.datepicker').datepicker();
	$('#bookissue_form').validationEngine();
	
	$('#member_list').DataTable({
	"order": [[ 1, "asc" ]],
		 "aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}],
		language:<?php echo smgt_datatable_multi_language();?>
		});
	
} );
</script>	
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
<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="<?php if($active_tab=='memberlist'){?>active<?php }?>">
          <a href="?dashboard=user&page=library&tab=memberlist"  class="tab <?php echo $active_tab == 'memberlist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php _e('Member List', 'school-mgt'); ?></a>
          </a>
      </li>
		<li class="<?php if($active_tab=='booklist'){?>active<?php }?>">
          <a href="?dashboard=user&page=library&tab=booklist"  class="tab <?php echo $active_tab == 'booklist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php _e('Book List', 'school-mgt'); ?></a>
          </a>
      </li>
    	<?php if($school_obj->role=='supportstaff')
		{?>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
			<li class="<?php if($active_tab=='addbook'){?>active<?php }?>">
				<a href="?dashboard=user&page=library&tab=addbook&action=edit&book_id=<?php echo $_REQUEST['book_id'];?>"  class="tab <?php echo $active_tab	 == 'addbook' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Edit Book', 'school-mgt'); ?></a>
				</a>
			</li>

		<?php 
		}
		else
		{?>
		
			<li class="<?php if($active_tab=='addbook'){?>active<?php }?>">
				<a href="?dashboard=user&page=library&tab=addbook"  class="tab <?php echo $active_tab	 == 'addbook' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Book', 'school-mgt'); ?></a>
				</a>
			</li>
		
        <?php }?> 
		
		<li class="<?php if($active_tab=='issuelist'){?>active<?php }?>">
          <a href="?dashboard=user&page=library&tab=issuelist"  class="tab <?php echo $active_tab == 'issuelist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php _e('Issue List', 'school-mgt'); ?></a>
          </a>
      </li>
		
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'issuebook')
		{?>
		
			<li class="<?php if($active_tab=='issuebook'){?>active<?php }?>">
				<a href="?dashboard=user&page=library&tab=issuebook&action=edit&issuebook_id=<?php echo $_REQUEST['issuebook_id'];?>"  class="tab <?php echo $active_tab	 == 'issuebook' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Edit Issue Book', 'school-mgt'); ?></a>
				</a>
			</li>
		
		<?php 
		}
		else
		{?>
	
		<li class="<?php if($active_tab=='issuebook'){?>active<?php }?>">
				<a href="?dashboard=user&page=library&tab=issuebook"  class="tab <?php echo $active_tab	 == 'issuebook' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Issue Book', 'school-mgt'); ?></a>
				</a>
			</li>
	
        <?php }
		}?> 
    </ul>
    <div class="tab-content">
    <?php
	if($active_tab == 'booklist')
	{?>
	<script>
jQuery(document).ready(function() {
	var table =  jQuery('#book_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[<?php if($school_obj->role=='supportstaff'){?>	                  
	                  {"bSortable": false},
					  <?php }?>
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                
	                  {"bSortable": true},
	                  {"bSortable": true},	                  
	                  {"bSortable": false}],
		language:<?php echo smgt_datatable_multi_language();?>
    });
	 jQuery('#checkbox-select-all').on('click', function(){
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
      jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
   }); 
   
	jQuery('#delete_selected').on('click', function(){
		 var c = confirm('Are you sure to delete?');
		if(c){
			jQuery('#frm-example').submit();
		}
		
	});
   
});

</script>
		<div class="panel-body">
		<form id="frm-example" name="frm-example" method="post">
        <table id="book_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				
				<?php if($school_obj->role=='supportstaff'){?>
              <th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>
				<?php } ?>
				<th><?php _e('ISBN','school-mgt');?></th>
                <th><?php _e('Book Name','school-mgt');?></th>
                <th><?php _e('Author Name','school-mgt');?></th>
                <th><?php _e('Rack Location','school-mgt');?></th>
				<th><?php _e('Quantity','school-mgt');?></th>
                <?php if($school_obj->role=='supportstaff'){?>
                <th><?php _e('Action','school-mgt');?></th>
				<?php } ?>
            </tr>
        </thead>
		<tfoot>
            <tr>
				
				<?php if($school_obj->role=='supportstaff'){?>
               <th></th>
				<?php } ?>
              	<th><?php _e('ISBN','school-mgt');?></th>
                <th><?php _e('Book Name','school-mgt');?></th>
                <th><?php _e('Author Name','school-mgt');?></th>
                <th><?php _e('Rack Location','school-mgt');?></th>
				<th><?php _e('Quantity','school-mgt');?></th>
				<?php if($school_obj->role=='supportstaff'){?>
                <th><?php _e('Action','school-mgt');?></th>
				<?php } ?>
            </tr>
        </tfoot>
		<tbody>
         <?php $retrieve_books=$obj_lib->get_all_books(); 
			if(!empty($retrieve_books))
			{
				foreach ($retrieve_books as $retrieved_data){ ?>
				<tr>
				 <?php if($school_obj->role=='supportstaff'){?>
					<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->id;?>"></td>
				<?php }?>
					<td><?php echo $retrieved_data->ISBN;?></td>
					<td><?php echo stripslashes($retrieved_data->book_name);?></td>
					<td><?php echo stripslashes($retrieved_data->author_name);?></td>
					<td><?php echo get_the_title($retrieved_data->rack_location);?></td>
					<td><?php echo $retrieved_data->quentity;?></td>
					<?php if($school_obj->role=='supportstaff')
					{?>
					<td> <a href="?dashboard=user&page=library&tab=addbook&action=edit&book_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"><?php _e('Edit','school-mgt');?> </a>
					<a href="?dashboard=user&page=library&tab=booklist&action=delete&book_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php _e('Delete','school-mgt');?></a> 
					</td>
				   
				</tr>
				<?php } 
				}
			}?>	
     
        </tbody>
        
        </table> <?php if($school_obj->role=='supportstaff'){?>
         <div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php _e('Delete Selected','school-mgt');?>" name="delete_selected_book" class="btn btn-danger delete_selected"/>
			
		</div>
		<?php }?>
		</form>
        </div>
  
     <?php 
	 }
	if($active_tab == 'addbook')
	 {?>
		<script type="text/javascript">
			$(document).ready(function() {
				 $('#book_form').validationEngine();
			} );
			</script>
			<?php $bookid=0;
				if(isset($_REQUEST['book_id']))
					$bookid=$_REQUEST['book_id'];
				$edit=0;
			 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				 $edit=1;
				 $result=$obj_lib->get_single_books($bookid);
				 
			}?>
       
        <div class="panel-body">	
        <form name="book_form" action="" method="post" class="form-horizontal" id="book_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="book_id" value="<?php echo $bookid;?>">
        <div class="form-group">
			<label class="col-sm-2 control-label" for="isbn"><?php _e('ISBN','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="isbn" class="form-control validate[required]" type="text" value="<?php if($edit){ echo $result->ISBN;}?>" name="isbn">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="category_data"><?php _e('Book Category','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="bookcat_id" id="category_data" class="form-control validate[required]">
					<option value = ""><?php _e('Select Category','school-mgt');?></option>
					<?php 
					if($edit)
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
			<div class="col-sm-2">
				<button id="addremove" model="book_cat"><?php _e('Add Or Remove','school-mgt');?></button>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="book_name"><?php _e('Book Name','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>
			<div class="col-sm-8">
				<input id="book_name" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo stripslashes($result->book_name);}?>" name="book_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="author_name"><?php _e('Author Name','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>
			<div class="col-sm-8">
				<input id="author_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" type="text" value="<?php if($edit){ echo stripslashes($result->author_name);}?>" name="author_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="category_data"><?php _e('Rack Location','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="rack_id" id="rack_category_data" class="form-control validate[required]">
					<option value = ""><?php _e('Select Rack Location','school-mgt');?></option>
					<?php 
					if($edit)
						$rack_location = $result->rack_location;
					$rack_data = $obj_lib->smgt_get_racklist();
				
					if(!empty($rack_data))
					{
						foreach ($rack_data as $retrieved_data)
						{
							echo '<option value="'.$retrieved_data->ID.'" '.selected($rack_location,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';
						}
					} ?>
			</select>
			</div>
			<div class="col-sm-2">
				<button id="addremove" model="rack_type"><?php _e('Add Or Remove','school-mgt');?></button>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="book_price"><?php _e('Price','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="book_price" class="form-control validate[custom[number]]" type="text" value="<?php if($edit){ echo $result->price;}?>" name="book_price" >
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="class_capacity"><?php _e('Quantity','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="quentity" class="form-control" type="number" value="<?php if($edit){ echo $result->quentity;}?>" name="quentity" style="width: 60px;">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="description"><?php _e('Description','school-mgt');?></label>
			<div class="col-sm-8">
				<textarea id="description" name="description" class="form-control" ><?php if($edit){ echo $result->description;}?>  </textarea>
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ _e('Save Book','school-mgt'); }else{ _e('Add Book','school-mgt');}?>" name="save_book" class="btn btn-success" />
        </div>
           	
        
        </form>
        </div>
       <?php
	 }
	 if($active_tab == 'issuelist')
	 {?>
		<script>
jQuery(document).ready(function() {
	var table =  jQuery('#issue_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                  
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                
	                  {"bSortable": true},
	                  {"bSortable": true},	                  
	                  {"bSortable": false}],
		language:<?php echo smgt_datatable_multi_language();?>
    });
	 jQuery('#checkbox-select-all').on('click', function(){
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
      jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
   }); 
   
	jQuery('#delete_selected').on('click', function(){
		 var c = confirm('Are you sure to delete?');
		if(c){
			jQuery('#frm-example').submit();
		}
		
	});
   
});

</script>
		<div class="panel-body">
		<form id="frm-example" name="frm-example" method="post">
        <table id="issue_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th> 
				<th><?php _e('Student Name','school-mgt');?></th>
                <th><?php _e('Book Name','school-mgt');?></th>
                <th><?php _e('Issue Date','school-mgt');?></th>
                <th><?php _e('Return Date ','school-mgt');?></th>
				<th><?php _e('Period','school-mgt');?></th>
                <th><?php _e('Action','school-mgt');?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
				<th></th>
              <th><?php _e('Student Name','school-mgt');?></th>
                <th><?php _e('Book Name','school-mgt');?></th>
                <th><?php _e('Issue Date','school-mgt');?></th>
                <th><?php _e('Return Date ','school-mgt');?></th>
				<th><?php _e('Period','school-mgt');?></th>
                <th><?php _e('Action','school-mgt');?></th>
            </tr>
        </tfoot>
		<tbody>
         <?php $retrieve_issuebooks=$obj_lib->get_all_issuebooks(); 
			if(!empty($retrieve_issuebooks))
			{
				foreach ($retrieve_issuebooks as $retrieved_data){ ?>
				<tr>
					<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->id;?>"></td>
					<td><?php  $student=get_userdata($retrieved_data->student_id);
							echo $student->display_name;?></td>
					<td><?php echo stripslashes(get_bookname($retrieved_data->book_id));?></td>
					<td><?php echo smgt_getdate_in_input_box($retrieved_data->issue_date);?></td>
					<td><?php echo smgt_getdate_in_input_box($retrieved_data->end_date);?></td>
					<td><?php echo get_the_title($retrieved_data->period);?></td>
					<td> <a href="?dashboard=user&page=library&tab=issuebook&action=edit&issuebook_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"><?php _e('Edit','school-mgt');?> </a>
					<a href="?dashboard=user&page=library&tab=issuelist&action=delete&issuebook_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php _e('Delete','school-mgt');?></a> 
					</td>
				   
				</tr>
				<?php } 
			}?>	
     
        </tbody>
        
        </table>
         <div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php _e('Delete Selected','school-mgt');?>" name="delete_selected_issuebook" class="btn btn-danger delete_selected"/>
			
		</div>
		</form>
        </div>
	<?php
	 }
	  if($active_tab == 'issuebook')
	{?>
				<script type="text/javascript">

		
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
        <form name="bookissue_form" action="" method="post" class="form-horizontal" id="bookissue_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="issue_id" value="<?php echo $issuebook_id;?>">
        
		<div class="form-group">
			<label class="col-sm-2 control-label" for="class_id"><?php _e('Class','school-mgt');?></label>
			<div class="col-sm-8">
			<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
				<select name="class_id" id="class_list" class="form-control">
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
				<input id="issue_date" class="datepicker form-control validate[required] text-input" type="text" name="issue_date" value="<?php if($edit){ echo $result->issue_date;}else{echo date('m/d/Y');}?>">
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
    <?php }
		if($active_tab == 'memberlist')
		{?>
		
		<div class="panel-body">
        <table id="member_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th><?php _e('Photo','school-mgt');?></th>
             <th><?php _e('Student Name','school-mgt');?></th>
                <th><?php _e('Class','school-mgt');?></th>
               <th><?php _e('Roll No','school-mgt');?></th>
                <th><?php _e('Student Email','school-mgt');?></th>
				<th><?php _e('Action','school-mgt');?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
              <th><?php _e('Photo','school-mgt');?></th>
             <th><?php _e('Student Name','school-mgt');?></th>
                <th><?php _e('Class','school-mgt');?></th>
               <th><?php _e('Roll No','school-mgt');?></th>
                <th><?php _e('Student Email','school-mgt');?></th>
				<th><?php _e('Action','school-mgt');?></th>
            </tr>
        </tfoot>
		<tbody>
         <?php //$retrieve_issuebooks=$obj_lib->get_all_issuebooks(); 
			$studentdata =$school_obj->student;
			if(!empty($studentdata))
			{
				foreach ($studentdata as $retrieved_data){ ?>
				<tr>
					<td class="user_image text-center"><?php $uid=$retrieved_data->ID;
							$umetadata=get_user_image($uid);
							if(empty($umetadata['meta_value'])){
								echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle" />';
							}
							else
							echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';?></td>
					<td class="name"><?php echo $retrieved_data->display_name;?></td>
					<td class="name"><?php $class_id=get_user_meta($retrieved_data->ID, 'class_name',true);
					echo $classname=get_class_name($class_id);?></td>
					<td class="roll_no"><?php echo get_user_meta($retrieved_data->ID, 'roll_id',true);?></td>
				<td class="email"><?php echo $retrieved_data->user_email;?></td>

					<td>
					<?php $stud_id=get_current_user_id();
					if($school_obj->role=='student')
					{
						if($stud_id==$retrieved_data->ID){?>
					<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup" class="btn btn-info"><?php _e('View','school-mgt');?></a>
					<?php } 
					}
					else
					{ ?> 
						<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup" class="btn btn-info"><?php _e('View','school-mgt');?></a>
						<?php if($school_obj->role=='supportstaff')
						{ ?>
						<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="accept_returns_book_popup" class="btn btn-success"><?php _e('Accept Returns','school-mgt');?> </a>
						
						<?php }
					} ?> 
					<!--<a href="?dashboard=user&page=library&tab=memberlist&action=delete&issuebook_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php _e('Delete','school-mgt');?></a> -->
					</td>
				   
				</tr>
				<?php } 
			}?>	
     
        </tbody>
        
        </table>
        
        </div>
	<?php
	 }
	?>
	 
	 </div>
	 </div>
	
<?php ?>