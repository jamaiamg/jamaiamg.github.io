<?php 
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'registration_mail';
if(isset($_REQUEST['save_registration_template']))
{
	update_option('registration_mailtemplate',$_REQUEST['registratoin_mailtemplate_content']);
	update_option('registration_title',$_REQUEST['registration_title']);	
	$search=array('{{student_name}}','{{school_name}}');
	$replace = array('ashvin','A1 School');
	$message_content = str_replace($search, $replace,get_option('registration_mailtemplate'));
}
if(isset($_REQUEST['save_activation_mailtemplate']))
{
	update_option('student_activation_mailcontent',$_REQUEST['activation_mailcontent']);
	update_option('student_activation_title',$_REQUEST['student_activation_title']);	
	$search=array('{{student_name}}','{{school_name}}');
	$replace = array('ashvin','A1 School');
	$message_content = str_replace($search, $replace,get_option('student_activation_mailcontent'));	
}
if(isset($_REQUEST['save_feepayment_mailtemplate']))
{
	
	update_option('fee_payment_mailcontent',$_REQUEST['fee_payment_mailcontent']);
	update_option('fee_payment_title',$_REQUEST['fee_payment_title']);	
	$search=array('{{student_name}}','{{parent_name}}','{{roll_number}}','{{class_name}}','{{fee_type}}','{{fee_amount}}','{{school_name}}');
	$replace = array('ashvin','Bhaskar','101','Two','First Sem Fee','5000','A1 School');
	$message_content = str_replace($search, $replace,get_option('student_activation_mailcontent'));	
} ?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('.sdate').datepicker({dateFormat: "yy-mm-dd"}); 
	$('.edate').datepicker({dateFormat: "yy-mm-dd"}); 

} );
</script>
<div class="page-inner" style="min-height:1631px !important">
	<div class="page-title"> 
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
		 <?php if(isset($_REQUEST['save_registration_template']) || isset($_REQUEST['save_activation_mailtemplate']))
					{ ?>
						<div class="alert alert-success" role="alert">
							<h3>
							<span class="dashicons dashicons-yes"></span>
							<?php _e('Saved Successfully ','school-mgt');?>
							</h3>
						</div>
			<?php } ?>
	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
						<h2 class="nav-tab-wrapper">
			    			<a href="?page=smgt_email_template&tab=registration_mail" class="nav-tab <?php echo $active_tab == 'registration_mail' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Registration Mail Template', 'school-mgt'); ?></a>
							<a href="?page=smgt_email_template&tab=activation_mail" class="nav-tab <?php echo $active_tab == 'activation_mail' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Student Activation Mail Template', 'school-mgt'); ?></a>
							<a href="?page=smgt_email_template&tab=feepayment_mail" class="nav-tab <?php echo $active_tab == 'feepayment_mail' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Fee Payment Mail Template', 'school-mgt'); ?></a>
						</h2> 
					<div class="clearfix"></div>
    	<?php 
    require_once SMS_PLUGIN_DIR. '/admin/includes/email-template/'.$active_tab.'.php';?>
			</div>
	</div>
</div>

