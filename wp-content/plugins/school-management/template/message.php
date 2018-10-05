<?php 
$active_tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'inbox';
?>
<div id="main-wrapper">
<div class="row mailbox-header">
                                <div class="col-md-2">
                                <?php if($school_obj->role == 'teacher' || $school_obj->role == 'parent' || 
								get_option('student_send_message')){?>
                                    <a class="btn btn-success btn-block" href="?dashboard=user&page=message&tab=compose">
                                    <?php _e("Compose","school-mgt");?></a>
                                   <?php }?>
                                </div>
                                <div class="col-md-6">
                                    <h2>
                                    <?php
									if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
                                    echo esc_html( __( 'Inbox', 'school-mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'sentbox')
									echo esc_html( __( 'Sent Item', 'school-mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'compose')
										echo esc_html( __( 'Compose', 'school-mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'view_message')
										echo esc_html( __( 'View Message', 'school-mgt' ) );
									?>
								
                                    
                                    </h2>
                                </div>
                               <!--   <div class="col-md-4">
                                    <form method="GET" action="#">
                                        <div class="input-group">
                                            <input type="text" placeholder="Search..." class="form-control input-search" name="search">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-success"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>Input Group 
                                    </form>
                               </div>
                               -->
                            </div>
 <div class="col-md-2">
                            <ul class="list-unstyled mailbox-nav">
 								<li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
 									<a href="?dashboard=user&page=message&tab=inbox">
 										<i class="fa fa-inbox"></i><?php _e("Inbox","school-mgt");?> <span class="badge badge-success pull-right">
 										<?php echo count(smgt_count_unread_message(get_current_user_id()));?></span>
 									</a>
 								</li>
 								<?php if($school_obj->role == 'teacher' || $school_obj->role == 'parent' || 
								get_option('student_send_message')){?>
                                <li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>><a href="?dashboard=user&page=message&tab=sentbox"><i class="fa fa-sign-out"></i><?php _e("Sent","school-mgt");?></a></li>
                                <?php }?>                                
                            </ul>
                        </div>
 <div class="col-md-10">
 <?php  
 	if($active_tab == 'sentbox')
 		require_once SMS_PLUGIN_DIR. '/template/sendbox.php';
 	if($active_tab == 'inbox')
 		require_once SMS_PLUGIN_DIR. '/template/inbox.php';
 	if($active_tab == 'compose')
 		require_once SMS_PLUGIN_DIR. '/template/composemail.php';
 	if($active_tab == 'view_message')
 		require_once SMS_PLUGIN_DIR. '/template/view_message.php';
 	
 	?>
 </div>
</div><!-- Main-wrapper -->
<?php ?>