<?php $obj_lib = new Smgtlibrary();	?>
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
				<textarea id="description" name="description" class="form-control"><?php if($edit){ echo $result->description;}?> </textarea>
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ _e('Save Book','school-mgt'); }else{ _e('Add Book','school-mgt');}?>" name="save_book" class="btn btn-success" />
        </div>
           	
        
        </form>
        </div>
       
<?php ?>