<?php
class Smgtlibrary
{
	public function add_book($data)
	{
		global $wpdb;
		$table_book=$wpdb->prefix.'smgt_library_book';
		$bookdata['ISBN']=$data['isbn'];
		$bookdata['book_name']=$data['book_name'];
		$bookdata['author_name']=$data['author_name'];
		$bookdata['cat_id']=$data['bookcat_id'];
		$bookdata['rack_location']=$data['rack_id'];
		$bookdata['price']=$data['book_price'];
		$bookdata['quentity']=$data['quentity'];
		$bookdata['description']=$data['description'];
		$bookdata['added_by']=get_current_user_id();
		$bookdata['added_date']=date('Y-m-d');
		
		if($data['action']=='edit')
		{
			$book_id['id']=$data['book_id'];
			$result=$wpdb->update( $table_book, $bookdata ,$book_id);
			return $result;
		}
		else
		{
			
			$result=$wpdb->insert( $table_book,$bookdata);
			return $result;
		}
	}
	public function get_all_books()
	{
		global $wpdb;
		$table_book=$wpdb->prefix.'smgt_library_book';
		
		$result = $wpdb->get_results("SELECT * FROM $table_book");
		return $result;
		
	}
	public function get_single_books($id)
	{
		global $wpdb;
		$table_book=$wpdb->prefix.'smgt_library_book';
		$result = $wpdb->get_row("SELECT * FROM $table_book where id=".$id);
		return $result;
		
	}
	public function smgt_get_bookcat()
	{
		$args= array('post_type'=> 'smgt_bookcategory','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
		$result = get_posts( $args );		
		return $result;		
	}
	public function smgt_add_bookcat($data)
	{
		global $wpdb;
		$result = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => 'smgt_bookcategory',
						'post_title' => $data['category_name']) );
		
			return $result;			
	}
	public function delete_cat_type($cat_id)
	{
		$result=wp_delete_post($cat_id);
		
		return $result;
	}
	public function smgt_get_racklist()
	{
		$args= array('post_type'=> 'smgt_rack','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
		$result = get_posts( $args );		
		return $result;		
	}
	public function smgt_add_rack($data)
	{
		global $wpdb;
		$result = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => 'smgt_rack',
						'post_title' => $data['category_name']) );
		
			return $result;			
	}
	public function delete_rack_type($cat_id)
	{
		$result=wp_delete_post($cat_id);
		
		return $result;
	}
	public function delete_book($id)
	{
		global $wpdb;
		$table_book=$wpdb->prefix.'smgt_library_book';
		$result = $wpdb->query("DELETE FROM $table_book where id= ".$id);
		return $result;
	}
	public function smgt_add_period($data)
	{
		global $wpdb;
		$result = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => 'smgt_bookperiod',
						'post_title' => $data['category_name']) );
		
			return $result;			
	}
	public function smgt_get_periodlist()
	{
		$args= array('post_type'=> 'smgt_bookperiod','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
		$result = get_posts( $args );		
		return $result;		
	}
	public function delete_period($cat_id)
	{
		$result=wp_delete_post($cat_id);
		
		return $result;
	}
	public function add_issue_book($data)
	{
		
		global $wpdb;
		$table_issue=$wpdb->prefix.'smgt_library_book_issue';
		$issuedata['class_id']=$data['class_id'];
		if(isset($data['class_section']))
		$issuedata['section_id']=$data['class_section'];
		$issuedata['student_id']=$data['student_id'];
		$issuedata['cat_id']=$data['bookcat_id'];
		$issuedata['issue_date']=$data['issue_date'];
		$issuedata['end_date']=$data['return_date'];
		$issuedata['period']=$data['period_id'];
		$issuedata['fine']=0;
		if(isset($data['fine']))
			$issuedata['fine']=$data['fine'];
		$issuedata['status']='Issue';
		$issuedata['issue_by']=get_current_user_id();
		if($data['action']=='edit')
		{
			$issue_id['id']=$data['issue_id'];
			foreach($data['book_id'] as $book)
			{
				$issuedata['book_id']=$book;
				$result=$wpdb->update( $table_issue, $issuedata ,$issue_id);
			}
			return $result;
		}
		else
		{
			foreach($data['book_id'] as $book)
			{
				$issuedata['book_id']=$book;
				$result=$wpdb->insert( $table_issue,$issuedata);
			}
			
			
			return $result;
		}
	}
	public function get_all_issuebooks()
	{
		global $wpdb;
		$table_issuebook=$wpdb->prefix.'smgt_library_book_issue';
		
		$result = $wpdb->get_results("SELECT * FROM $table_issuebook");
		return $result;
		
	}
	public function get_single_issuebooks($id)
	{
		global $wpdb;
		$table_issuebook=$wpdb->prefix.'smgt_library_book_issue';
		$result = $wpdb->get_row("SELECT * FROM $table_issuebook where id=".$id);
		return $result;
		
	}
	public function delete_issuebook($id)
	{
		global $wpdb;
		$table_issuebook=$wpdb->prefix.'smgt_library_book_issue';
		$result = $wpdb->query("DELETE FROM $table_issuebook where id= ".$id);
		return $result;
	}
	public function submit_return_book($data)
	{
		//var_dump($data);
		
		global $wpdb;
		$table_issuebook=$wpdb->prefix.'smgt_library_book_issue';
		foreach($data['books_return'] as $key=>$book_id)
		{
				$issue_id['id']=$book_id;
				$issuedata['status']='Submitted';
				$issuedata['fine']=$data['fine'][$key];
				$issuedata['actual_return_date']=date('Y-m-d');
				$result=$wpdb->update( $table_issuebook, $issuedata ,$issue_id);
		}
		return $result;
	}
}
 ?>