<?php 
//notice
?>
<script>
$(document).ready(function() {
    $('#notice_list').DataTable({
        responsive: true
    });
} );
</script>
<!-- View Popup Code -->	
<div class="popup-bg">
    <div class="overlay-content">
    
    	<div class="notice_content"></div>    
    </div>
  </div>


<div class="panel-body panel-white">
 <ul class="nav nav-tabs panel_tabs" role="tablist">
      <li class="active">
          <a href="#examlist" role="tab" data-toggle="tab">
             <i class="fa fa-align-justify"></i> <?php _e('Notice List', 'school-mgt'); ?></a>
          </a>
      </li>
</ul>
	<div class="tab-content">
    	
         <?php 
		 //	$retrieve_class = get_all_data($tablename);		
		?>
		<div class="panel-body">
        <div class="table-responsive">
        <table id="notice_list"class="display dataTable" cellspacing="0" width="100%">
        	 <thead>
            <tr>                
                <th width="190px"><?php _e( 'Notice Title', 'school-mgt' ) ;?></th>
                <th><?php _e( 'Notice Comment', 'school-mgt' ) ;?></th>
                <th><?php _e('Notice Start Date','school-mgt');?></th>
				<th><?php _e('Notice End Date','school-mgt');?></th>
                <th><?php _e( 'Notice For', 'school-mgt' ) ;?></th>
                <th width="60px"><?php _e( 'Action', 'school-mgt' ) ;?></th>
                     
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th><?php  _e( 'Notice Title', 'school-mgt' ) ;?></th>
                <th><?php  _e( 'Notice Comment', 'school-mgt' ) ;?></th>
                <th><?php _e('Notice Start Date','school-mgt');?></th>
				<th><?php _e('Notice End Date','school-mgt');?></th>
                <th><?php  _e( 'Notice For', 'school-mgt' ) ;?></th>
                <th><?php _e( 'Action', 'school-mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
		  $args['post_type'] = 'notice';
		  $args['posts_per_page'] = -1;
		  $args['post_status'] = 'public';
		  $q = new WP_Query();
	$retrieve_class = $school_obj->notice;
	
	$format =get_option('date_format') ;
	if($school_obj->role=='student'){
		 	foreach ($retrieve_class as $postid){ 
					$retrieved_data=get_post($postid);?>
            <tr>
                <td><?php echo $retrieved_data->post_title;?></td>
                <td><?php 
					$strlength= strlen($retrieved_data->post_content);
					if($strlength > 60)
						echo substr($retrieved_data->post_content, 0,60).'...';
					else
						echo $retrieved_data->post_content;
				
				?></td>
               <td><?php echo smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'start_date',true));?></td> 
				<td><?php echo smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'end_date',true));?></td> 			      
                <td><?php echo get_post_meta( $retrieved_data->ID, 'notice_for',true);?></td>        
                <td>  <a href="#" class="btn btn-primary view-notice" id="<?php echo $retrieved_data->ID;?>"> <?php _e('View','school-mgt');?></a>     </td>       
               
            </tr>
            <?php } 
	}
	else
	{   
			foreach ($retrieve_class as $retrieved_data){ 
					?>
            <tr>
                <td><?php echo $retrieved_data->post_title;?></td>
                <td><?php 
					$strlength= strlen($retrieved_data->post_content);
					if($strlength > 60)
						echo substr($retrieved_data->post_content, 0,60).'...';
					else
						echo $retrieved_data->post_content;
				
				?></td>
               <td><?php echo smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'start_date',true));?></td> 
				<td><?php echo smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'end_date',true));?></td> 			      
                <td><?php echo get_post_meta( $retrieved_data->ID, 'notice_for',true);?></td>        
                <td>  <a href="#" class="btn btn-primary view-notice" id="<?php echo $retrieved_data->ID;?>"> <?php _e('View','school-mgt');?></a>     </td>       
               
            </tr>
            <?php } 
		
		} ?>
        </tbody>
        
        </table>	
		</div>
		</div>
	</div>
</div>
<?php ?>