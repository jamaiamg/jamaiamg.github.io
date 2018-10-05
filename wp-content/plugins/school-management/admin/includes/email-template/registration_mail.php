<script type="text/javascript">
$(document).ready(function() {
	 $('#email_template_form').validationEngine();	
} );
</script>
<div class="panel-body">
<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
<div class="form-group">
				<label for="first_name" class="col-sm-3 control-label">Email Subject <span class="require-field">*</span></label>
				<div class="col-md-8">
					<input class="form-control validate[required]" name="registration_title" id="registration_title" placeholder="Enter email subject" value="<?php echo get_option('registration_title'); ?>">
				</div>
	</div>
	<div class="form-group">
				<label for="first_name" class="col-sm-3 control-label">Emails Sent to Student When A Student Register  <span class="require-field">*</span></label>
				<div class="col-md-8">
					<textarea name="registratoin_mailtemplate_content" class="form-control validate[required]"><?php echo get_option('registration_mailtemplate');?></textarea>
				</div>
	</div>
	<div class="form-group">
			<div class="col-sm-offset-3 col-md-8">
				<label><?php _e('You can use following variables in the email template:','school-mgt');?></label><br>
				<label><strong>{{student_name}} - </strong><?php _e('The student full name or login name (whatever is available)','school-mgt');?></label><br>
				<label><strong>{{user_name}} - </strong><?php _e('User name of student','school-mgt');?></label><br>
				<label><strong>{{class_name}} - </strong><?php _e('Class name of student','school-mgt');?></label><br>
				<label><strong>{{email}} - </strong><?php _e('Email of student','school-mgt');?></label><br>
				<label><strong>{{school_name}} - </strong><?php _e('School name','school-mgt');?></label>
					
			</div>
		</div>
	<div class="col-sm-offset-3 col-sm-8">
        	
        	<input type="submit" value="<?php  _e('Save','school-mgt')?>" name="save_registration_template" class="btn btn-success"/>
        </div>
</form>
</div>