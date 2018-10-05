<?php
class Class_routine
{
	public $route_id;
	public $subject_id;
	public $teacher_id;
	public $class_id;
	public $week_day;
	public $start_time;
	public $end_time;
	public $table_name = 'smgt_time_table';
	public $day_list = array('1'=>'Monday',
	                         '2' => 'Tuesday',
							 '3' => 'Wednesday',
							 '4' => 'Thursday',
							 '5' => 'Friday',
							 '6' => 'Saturday',
							 '7' => 'Sunday');
	
	function __cunstuctor($route_id = null)
	{
		if($route_id)
		{}
	}
	public function save_route($route_data)
	{
		
		$table_name = "smgt_time_table";
		//echo $table_name;
		insert_record($table_name,$route_data);	
	}
	public function update_route($route_data,$route_id)
	{
		$table_name = "smgt_time_table";
		update_record($table_name,$route_data,$route_id);
	}
	public function is_route_exist($route_data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . $this->table_name;
		$route =$wpdb->get_row("SELECT * FROM $table_name WHERE subject_id=".$route_data['subject_id']." AND teacher_id=".$route_data['teacher_id']." 
		AND class_id=".$route_data['class_id']." AND start_time='".$route_data['start_time']."' AND end_time='".$route_data['end_time']."' AND weekday=".$route_data['weekday']);
		
		$route2 = $wpdb->get_row("SELECT * FROM $table_name WHERE  teacher_id=".$route_data['teacher_id']." 
		 AND start_time='".$route_data['start_time']."' AND end_time='".$route_data['end_time']."' AND weekday=".$route_data['weekday']);
		if(empty($route) && empty($route2))
			return 'success';
		else
		{
			if(count($route) > 0)
				return 'duplicate';
			if(count($route2) > 0)
				return 'teacher_duplicate';
		}
			
	}
	
	public function get_periad($class_id,$section,$week_day)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . $this->table_name;
		$table_subject = $wpdb->prefix . 'subject';
		
		$route =$wpdb->get_results("SELECT * FROM $table_name as route,$table_subject as sb WHERE route.class_id=".$class_id." AND route.section_name=".$section." AND route.subject_id = sb.subid AND route.weekday=".$week_day);
		
		if(empty($route))
		{
			$route =$wpdb->get_results("SELECT * FROM $table_name as route,$table_subject as sb WHERE route.class_id=".$class_id." AND route.subject_id = sb.subid AND route.weekday=".$week_day);
		}
		
		return $route;
	}
	
	public function get_periad_by_teacher($teacher_id,$week_day)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . $this->table_name;
		$route =$wpdb->get_results("SELECT * FROM $table_name WHERE teacher_id=".$teacher_id." AND weekday=".$week_day);
		return $route;
	}
	
	
}
?>