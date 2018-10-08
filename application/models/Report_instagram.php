<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_instagram extends CI_Model {
	
	public function __construct()
	{
        parent::__construct();
    }
    public function summary($account_id, $type, $date)
	{
		$period = getmonth('date', $date);		
		switch($type){
			case 'Summary':			
				return array(
					'photo_post' => $this->summary($account_id, 'photo_post', $date),
					'video_post' => $this->summary($account_id, 'video_post', $date),
					'engagement_post' => $this->summary($account_id, 'engagement_post', $date),
					'like_post' => $this->summary($account_id, 'like_post', $date),
					'comment_post' => $this->summary($account_id, 'comment_post', $date),
					'feedback_total' => $this->summary($account_id, 'engagement_total', $date),
					'post' => $this->summary($account_id, 'post', $date)
				);
				break;				
			case 'carousel':
				return $this->db->from('feed')->where(array('account_id' => $account_id, 'type' => 'carousel', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get()->num_rows();
				break;				
			case 'photo_post':
				return $this->db->from('feed')->where(array('account_id' => $account_id, "type IN('image', 'carousel')" => NULL, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get()->num_rows();
				break;				
			case 'video_post':
				return $this->db->from('feed')->where(array('account_id' => $account_id, 'type' => 'video', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get()->num_rows();
				break;				
			case 'video_view':
				return $this->db->select('SUM(view_count) AS total')->from('feed')->where(array('account_id' => $account_id, 'type' => 'video', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get()->row()->total;
				break;				
			case 'like_post':
				$Qlist = $this->db->select("IFNULL(SUM(likes_count), 0) AS total")->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;				
			case 'comment_post':
				$Qlist = $this->db->select("IFNULL(SUM(comment_count), 0) AS total")->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;				
			case 'engagement_post':
				$total = $this->summary($account_id, 'photo_post', $date) + $this->summary($account_id, 'video_post', $date);
				return $this->summary($account_id, 'engagement_total', $date) / ($total ? $total : 1);
				break;				
			case 'engagement_total':
				$Qlist = $this->db->select("(IFNULL(SUM(likes_count), 0) + IFNULL(SUM(comment_count), 0)) AS total")->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;			
			case 'post':
				$Qlist = $this->db->select("(likes_count + comment_count) AS total, image_url, video_url, view_count, likes_count, comment_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('total', 'DESC')->limit(1)->get();
				if($Qlist->num_rows()){
					$list = $Qlist->row();
					$post = array(
						'permalink' => $list->permalink,
						'image_url' => $list->image_url,
						'video_url' => $list->video_url,
						'view_count' => $list->view_count,
						'likes_count' => $list->likes_count,
						'comment_count' => $list->comment_count,
						'type' => $list->type,
						'text' => $list->text,
						'post_time' => $list->post_time
					);
				}
				else $post = array();				
				return $post;
				break;
		}
	}
	function posts($account_id, $date, $where=array())
	{
		$period = getmonth('date', $date);
		$Qlist = $this->db->select('feed_id')->from('feed')->where(array_merge($where, array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL)))->get();
		if($Qlist->num_rows()){
			$ids = '';
			foreach($Qlist->result() as $list){
				connection_abort();
				$ids .= $ids ? ",'$list->feed_id'" : "'$list->feed_id'";
			}
		}
		else $ids = "'0'";
		return $ids;
	}
	public function primetime($account_id, $type, $date)
	{
		switch($type){
			case 'DailyPost':
				$period = getmonth('date', $date[0]);
				return $this->db->select('feed_id')->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "DATE_FORMAT(post_time, '%H/%w') =" => $date[1]))->get()->num_rows();
				break;				
			case 'SumPost':
				$period = getmonth('date', $date[0]);
				return $this->db->select('feed_id')->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "DATE_FORMAT(post_time, '$date[1]') =" => $date[2]))->get()->num_rows();
				break;				
			case 'DailyComment':
				$feeds = $this->posts($account_id, $date[0]);
				return $this->db->select('comment_id')->from('comment')->where(array("feed_id IN($feeds)" => NULL, "DATE_FORMAT(created_time, '%H/%w') =" => $date[1]))->get()->num_rows();
				break;				
			case 'SumComment':
				$feeds = $this->posts($account_id, $date[0]);
				return $this->db->select('comment_id')->from('comment')->where(array("feed_id IN($feeds)" => NULL, "DATE_FORMAT(created_time, '$date[1]') =" => $date[2]))->get()->num_rows();
				break;				
			case 'HourComment':
				$feeds = $this->posts($account_id, $date);
				$Qlist = $this->db->query("
					SELECT COUNT(1) AS `total`, DATE_FORMAT(`created_time`, '%H') AS `hour`
					FROM `comment`
					WHERE `feed_id` IN($feeds)
					GROUP BY DATE_FORMAT(`created_time`, '%H')
					ORDER BY DATE_FORMAT(`created_time`, '%H') ASC
				");
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'PrimeTime':
				$feeds = $this->posts($account_id, $date);
				$Qlist = $this->db->select('COUNT(1) AS total, created_time')->from('comment')->where(array("feed_id IN($feeds)" => NULL))->group_by("DATE_FORMAT(created_time, '%H')")->order_by('total', 'DESC')->limit(1)->get();
				if($Qlist->num_rows()){
					return $Qlist->row();
				}
				else return NULL	;
				break;
		}
	}
	public function feeds($account_id, $type, $date)
	{
		$period = getmonth('date', $date);		
		switch($type){
			case 'AllTopPost':
				$Qlist = $this->db->select("(likes_count + comment_count + share_count) AS total, likes_count, comment_count, share_count, post_id, image_url, video_url, view_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('total', 'DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'AllLeastPost':
				$Qlist = $this->db->select("(likes_count + comment_count + share_count) AS total, likes_count, comment_count, share_count, post_id, image_url, video_url, view_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('total', 'ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'LikeTopPost':
				$Qlist = $this->db->select("(likes_count + comment_count + share_count) AS total, likes_count, comment_count, share_count, post_id, image_url, video_url, view_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, 'likes_count > 0' => NULL, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('likes_count', 'DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'LikeLeastPost':
				$Qlist = $this->db->select("(likes_count + comment_count + share_count) AS total, likes_count, comment_count, share_count, post_id, image_url, video_url, view_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, 'likes_count > 0' => NULL, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('likes_count', 'ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'CommentTopPost':
				$Qlist = $this->db->select("(likes_count + comment_count + share_count) AS total, likes_count, comment_count, share_count, post_id, image_url, video_url, view_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, 'comment_count > 0' => NULL, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('comment_count', 'DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'CommentLeastPost':
				$Qlist = $this->db->select("(likes_count + comment_count + share_count) AS total, likes_count, comment_count, share_count, post_id, image_url, video_url, view_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, 'comment_count > 0' => NULL, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('comment_count', 'ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'VideoTopPost':
				$Qlist = $this->db->select("(likes_count + comment_count + share_count) AS total, likes_count, comment_count, share_count, post_id, image_url, video_url, view_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, 'type' => 'video', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('total', 'DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'VideoLeastPost':
				$Qlist = $this->db->select("(likes_count + comment_count + share_count) AS total, likes_count, comment_count, share_count, post_id, image_url, video_url, view_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, 'type' => 'video', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('total', 'ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'ViewVideoTopPost':
				$Qlist = $this->db->select("(likes_count + comment_count + share_count) AS total, likes_count, comment_count, share_count, post_id, image_url, video_url, view_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, 'type' => 'video', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('view_count', 'DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'ViewVideoLeastPost':
				$Qlist = $this->db->select("(likes_count + comment_count + share_count) AS total, likes_count, comment_count, share_count, post_id, image_url, video_url, view_count, type, text, permalink, post_time")->from('feed')->where(array('account_id' => $account_id, 'type' => 'video', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->group_by('post_id')->order_by('view_count', 'ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;
		}
	}
	public function activities($account_id, $type, $date)
	{
		switch($type){
			case 'Summary':			
				return array(
					'photo_post' => $this->summary($account_id, 'photo_post', $date),
					'video_post' => $this->summary($account_id, 'video_post', $date),
					'engagement_total' => $this->summary($account_id, 'engagement_total', $date),
				);
			case 'ActivitiesChart':
				$period = getmonth('date', $date);
				$session = date('Y-m', strtotime($period[0]));
				$month = date('m', strtotime($session.'-01'));
				for($i=0; $i < count_weeks($session.'-01'); $i++){
					if($i){
						$range = range_week($end);
						$week[$i][0] = $range['start'];
						$week[$i][1] = filter_month($month, $range['end']);
					}else{
						$range = range_week($session.'-01');
						$week[$i][0] = filter_month($month, $range['start']);
						$week[$i][1] = $range['end'];
					}
					$end = date('Y-m-d', strtotime('+7 days', strtotime($range['end'])));
				}				
				return isset($week) ? $week : array();
				break;				
			case 'ActivitiesDetail':
				$period = getmonth('unix', $date);
				$data['like_photo'] = array();
				$data['like_video'] = array();
				$data['comment_photo'] = array();
				$data['comment_video'] = array();
				$data['view_photo'] = array();
				$data['view_video'] = array();
				for ($i=3; $i >= 0; $i--) { 
					$d = date('01-M-Y ~ t-M-Y', strtotime('-' . $i . ' Month', $period[0]));
					$photo_post = $this->summary($account_id, 'photo_post', $d);
					$video_post = $this->summary($account_id, 'video_post', $d);
					array_push($data['like_photo'], round($this->activities($account_id, 'like_photo', $date) / ($photo_post ? $photo_post : 1)));
					array_push($data['like_video'], round($this->activities($account_id, 'like_video', $date) / ($video_post ? $video_post : 1)));
					array_push($data['comment_photo'], round($this->activities($account_id, 'comment_photo', $date) / ($photo_post ? $photo_post : 1)));
					array_push($data['comment_video'], round($this->activities($account_id, 'comment_video', $date) / ($video_post ? $video_post : 1)));
					array_push($data['view_photo'], round($this->activities($account_id, 'view_photo', $date) / ($photo_post ? $photo_post : 1)));
					array_push($data['view_video'], round($this->activities($account_id, 'view_video', $date) / ($video_post ? $video_post : 1)));				}
				return $data;
				break;				
			case 'post':
				return $this->db->from('feed')->where(array_merge($date, array('account_id' => $account_id)))->get()->num_rows();
				break;				
			case 'engagement':
				$Qlist = $this->db->select("(IFNULL(SUM(likes_count), 0) + IFNULL(SUM(comment_count), 0)) AS total")->from('feed')->where(array_merge($date, array('account_id' => $account_id)))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;				
			case 'like_photo':
				$period = getmonth('date', $date);
				$feeds = $this->posts($account_id, $date);
				$Qlist = $this->db->select("IFNULL(SUM(likes_count), 0) AS total")->from('feed')->where(array("feed_id IN($feeds)" => NULL, "type IN('image', 'carousel')" => NULL, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;				
			case 'like_video':
				$period = getmonth('date', $date);
				$feeds = $this->posts($account_id, $date);
				$Qlist = $this->db->select("IFNULL(SUM(likes_count), 0) AS total")->from('feed')->where(array("feed_id IN($feeds)" => NULL, 'type' => 'video', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;				
			case 'comment_photo':
				$period = getmonth('date', $date);
				$feeds = $this->posts($account_id, $date);
				$Qlist = $this->db->select("IFNULL(SUM(comment_count), 0) AS total")->from('feed')->where(array("feed_id IN($feeds)" => NULL, "type IN('image', 'carousel')" => NULL, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;				
			case 'comment_video':
				$period = getmonth('date', $date);
				$feeds = $this->posts($account_id, $date);
				$Qlist = $this->db->select("IFNULL(SUM(comment_count), 0) AS total")->from('feed')->where(array("feed_id IN($feeds)" => NULL, 'type' => 'video', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;
			case 'view_photo':
				$period = getmonth('date', $date);
				$feeds = $this->posts($account_id, $date);
				$Qlist = $this->db->select("IFNULL(SUM(view_count), 0) AS total")->from('feed')->where(array("feed_id IN($feeds)" => NULL, "type IN('image', 'carousel')" => NULL, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;				
			case 'view_video':
				$period = getmonth('date', $date);
				$feeds = $this->posts($account_id, $date);
				$Qlist = $this->db->select("IFNULL(SUM(view_count), 0) AS total")->from('feed')->where(array("feed_id IN($feeds)" => NULL, 'type' => 'video', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;
		}
	}
	public function performance($account_id, $type, $date)
	{
		switch($type){
			case 'Interaction':
				$period = getmonth('unix', $date);
				$month = date('Y-m', $period[0]);
				$competitor = $this->competitor($account_id);
				$Qlist = $this->db->query("
					SELECT a.`account_id`, a.`name`, a.`username`, a.`socmed_id`,
						IFNULL((SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '$month' ORDER BY `created_time` DESC LIMIT 1), 0) AS `fans`,
						IFNULL((SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$month'), 0) AS `post`,
						IFNULL((SELECT (SUM(`likes_count`) + SUM(`share_count`) + SUM(`comment_count`)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$month'), 0) AS `feedback`
					FROM `account` a
					WHERE a.`account_id` IN($competitor)
					ORDER BY `feedback` DESC
				");
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;
		}
	}
	public function competitor($id, $only=false)
	{
		$ids = $only ? '' : $id;
		$Qlist = $this->db->from('account_competitor')->where(array('main_account' => $id))->get();
		if($Qlist->num_rows()){
			foreach($Qlist->result() as $list){
				$ids .= $ids ? ','.$list->account_id : $list->account_id;
			}
		}
		return $ids;
	}
	public function growth($account_id, $type, $date)
	{
		switch($type){
			case 'CountPost':
				return $this->db->select('feed_id')->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '$date[0]') =" => $date[1]))->get()->num_rows();
				break;				
			case 'CountFans':
				$Qlist = $this->db->select('SUM(count) AS total')->from('follower')->where(array('account_id' => $account_id, "DATE_FORMAT(created_time, '$date[0]') =" => $date[1]))->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->total;
				}
				else return 0;
				break;				
			case 'DailyFans':
				$period = getmonth('date', $date);
				$Qlist = $this->db->from('follower')->where(array('account_id' => $account_id, "DATE_FORMAT(created_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->order_by('created_time', 'ASC')->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'DailyPrevious':
				$Qlist = $this->db->from('follower')->where(array('account_id' => $account_id, "DATE_FORMAT(created_time, '%Y-%m-%d') <" => $date))->order_by('created_time', 'DESC')->limit(1)->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->count;
				}
				else return NULL;
				break;				
			case 'MonthlyFans':
				$Qlist = $this->db->from('follower')->where(array('account_id' => $account_id, "DATE_FORMAT(created_time, '%Y-%m') =" => $date))->order_by('created_time', 'DESC')->limit(1)->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->count;
				}
				else return 0;
				break;
		}
	}
	public function users($account_id, $type, $date)
	{
		$period = getmonth('unix', $date);
		$from = date('Y-m', $period[0]);
		$to = date('Y-m', $period[1]);		
		switch($type){
			case 'MostCommenters':
				if(in_array($account_id, exc_account('n3o'), true)){
					$feeds = $this->posts($account_id, $date);
					$Qlist = $this->db->select("COUNT(1) AS t, b.socmed_id AS s, b.username AS u, b.name AS n, b.photo_profile AS p, IFNULL(b.city, b.location) AS l, b.follower AS f")->from('comment a')->join('user b', 'a.user_id=b.user_id')->where(array("a.feed_id IN($feeds)" => NULL))->group_by('a.user_id')->order_by('t', 'DESC')->limit(10)->get();
					if($Qlist->num_rows()){
						return $Qlist->result();
					}
					else return NULL;					
				}else{
					
					$Qlist = $this->db->select("a.comment_count AS t, b.socmed_id AS s, b.username AS u, b.name AS n, b.photo_profile AS p, IFNULL(b.city, b.location) AS l, IFNULL(a.follower, b.follower) AS f")->from('user_active a')->join('user b', 'a.user_id=b.user_id')->where(array('a.account_id'=> $account_id, "a.is_hide IS NULL" => NULL, 'a.comment_count <>' => 0, "a.created_time" => "{$to}-01"))->order_by('a.comment_count', 'DESC')->limit(10)->get();
					if($Qlist->num_rows()){
						return $Qlist->result();
					}
					else return NULL;
				}
				break;				
			case 'MostLikers':
				if(in_array($account_id, exc_account('n3o'), true)){
					$feeds = $this->posts($account_id, $date);
					$Qlist = $this->db->select("COUNT(1) AS t, b.socmed_id AS s, b.username AS u, b.name AS n, b.photo_profile AS p, IFNULL(b.city, b.location) AS l, b.follower AS f")->from('likes a')->join('user b', 'a.user_id=b.user_id')->where(array("a.feed_id IN($feeds)" => NULL))->group_by('a.user_id')->order_by('t', 'DESC')->limit(10)->get();
					if($Qlist->num_rows()){
						return $Qlist->result();
					}
					else return NULL;					
				}else{					
					$Qlist = $this->db->select("a.likes_count AS t, b.socmed_id AS s, b.username AS u, b.name AS n, b.photo_profile AS p, IFNULL(b.city, b.location) AS l, IFNULL(a.follower, b.follower) AS f")->from('user_active a')->join('user b', 'a.user_id=b.user_id')->where(array('a.account_id'=> $account_id, "a.is_hide IS NULL" => NULL, 'a.likes_count <>' => 0, "a.created_time" => "{$to}-01"))->order_by('a.likes_count', 'DESC')->limit(10)->get();
					if($Qlist->num_rows()){
						return $Qlist->result();
					}
					else return NULL;
				}
				break;				
			case 'MostActive':
				if(in_array($account_id, exc_account('n3o'), true)){
					$feeds = $this->posts($account_id, $date);
					$Qlist = $this->db->query("
						SELECT SUM(x.`activity`) AS `t`, x.`user_id`, b.`socmed_id` AS `s`, b.`username` AS `u`, b.`name` AS `n`, b.`photo_profile` AS `p`, IFNULL(b.`city`, b.`location`) AS `l`, b.`follower` AS `f` FROM (
							SELECT COUNT(1) AS `activity`, `user_id` FROM `comment` WHERE `feed_id` IN($feeds) GROUP BY `user_id`
							UNION ALL
							SELECT COUNT(1) AS `activity`, `user_id` FROM `likes` WHERE `feed_id` IN($feeds) GROUP BY `user_id`
							UNION ALL
							SELECT COUNT(1) AS `activity`, `user_id` FROM `share` WHERE `feed_id` IN($feeds) GROUP BY `user_id`
						) AS x
						JOIN `user` b ON x.`user_id`=b.`user_id`
						GROUP BY b.`socmed_id`
						ORDER BY `t` DESC LIMIT 10
					");
					if($Qlist->num_rows()){
						return $Qlist->result();
					}
					else return NULL;					
				}else{					
					$Qlist = $this->db->select("a.activity_count AS t, b.socmed_id AS s, b.username AS u, b.name AS n, b.photo_profile AS p, IFNULL(b.city, b.location) AS l, IFNULL(a.follower, b.follower) AS f")->from('user_active a')->join('user b', 'a.user_id=b.user_id')->where(array('a.account_id'=> $account_id, "a.is_hide IS NULL" => NULL, 'a.activity_count <>' => 0, "a.created_time" => "{$to}-01"))->order_by('a.activity_count', 'DESC')->limit(10)->get();
					if($Qlist->num_rows()){
						return $Qlist->result();
					}
					else return NULL;
				}
				break;
		}
	}
	public function conversation($account_id, $type, $date)
	{
		$period = getmonth('date', $date);
		
		switch($type){
			case 'All':
				$result = array();
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->where("(
					text LIKE '%lifestyle%' OR
					text LIKE '%fashion%' OR
					text LIKE '%nongkrong%' OR
					text LIKE '%trend%' OR
					text LIKE '%FBO%' OR
					text LIKE '%kuliner%' OR
					text LIKE '%food truck%' OR
					text LIKE '%food festival%' OR
					text LIKE '%jalan jalan%' OR
					text LIKE '%hobi%' OR
					text LIKE '%wisata%' OR
					text LIKE '%midnight sale%' OR
					text LIKE '%desainer%' OR
					text LIKE '%foodie land%' OR
					text LIKE '%foodieland%' OR
					text LIKE '%gaya hidup%' OR
					text LIKE '%Style%' OR
					text LIKE '%FestivalBelanjaOnline%' OR
					text LIKE '%FBO2014%' OR
					text LIKE '%FBO14%' OR
					text LIKE '%Festival Belanja Online%' OR
					text LIKE '%café%' OR
					text LIKE '%cafe%' OR
					text LIKE '%kafe%' OR
					text LIKE '%tempat dugem%' OR
					text LIKE '%Party%' OR
					text LIKE '%Clubbing%' OR
					text LIKE '%IFW2014%' OR
					text LIKE '%Clothing%' OR
					text LIKE '%Code2Ibiza%' OR
					text LIKE '%clubbers%' OR
					text LIKE '%JAKCLOTH%' OR
					text LIKE '%Distro%' OR
					text LIKE '%TOYS & GAMES%' OR
					text LIKE '%Traveller%' OR
					text LIKE '%vacation%' OR
					text LIKE '%Liburan%' OR
					text LIKE '%holiday%' OR
					text LIKE '%Diving%' OR
					text LIKE '%jfw2015%' OR
					text LIKE '%ifw2016%' OR
					text LIKE '%meme%'
				)")->get();
				$temp = array(	
					'category' => 'Lifestyle',
					'conversation' => $Qlist->num_rows(),
					'totalFeedback' => 0
				);
				foreach ($Qlist->result() as $row) {
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->where("(
					text LIKE '%film%' OR
					text LIKE '%Movie%' OR
					text LIKE '%Aktor%' OR
					text LIKE '%Aktris%' OR
					text LIKE '%Cerita%' OR
					text LIKE '%brrravemovie%' OR
					text LIKE '%LOLProject%' OR
					text LIKE '%LAIMMovieDayOut%' OR
					text LIKE '%filosofi kopi%' OR
					text LIKE '%filkopmovie%' OR
					text LIKE '%drama%' OR
					"./*--text LIKE '%action%' OR*/"
					text LIKE '%Genre film%' OR
					text LIKE '%cineplex%' OR
					text LIKE '%box office terbaru%' OR
					text LIKE '%bioskop%' OR
					text LIKE '%sutradara%' 
				)")->get();
				$temp = array(	
					'category' => 'Film',
					'conversation' => $Qlist->num_rows(),
					'totalFeedback' => 0
				);
				foreach ($Qlist->result() as $row) {
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->where("(
					text LIKE '%Gadget%' OR
					text LIKE '%handphone%' OR
					text LIKE '%Smartphone%' OR
					text LIKE '%jaknot%' OR
					text LIKE '%tablet%' OR
					text LIKE '%laptop%' OR
					text LIKE '%lenovo%' OR
					text LIKE '%kamera%' OR
					text LIKE '%xiaomi%' OR
					text LIKE '%camera%' OR
					text LIKE '%samsung%' OR
					text LIKE '%iphone%' OR
					text LIKE '%apple%' OR
					text LIKE '%aplikasi%' OR
					text LIKE '%windows%' OR
					text LIKE '%IOS%' OR
					text LIKE '%Flappy Bird%' OR
					text LIKE '%perangkat%' OR
					text LIKE '%komputer%' OR
					text LIKE '%computer%' OR
					text LIKE '%android%' OR
					text LIKE '%#Indocomtech2015%'
				)")->get();
				$temp = array(	
					'category' => 'Gadget',
					'conversation' => $Qlist->num_rows(),
					'totalFeedback' => 0
				);
				foreach ($Qlist->result() as $row) {
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->where("(
					text LIKE '%Musik%' OR
					text LIKE '%lagu%' OR
					text LIKE '%band%' OR
					text LIKE '%MTL2014%' OR
					text LIKE '%MeetTheLabels%' OR
					text LIKE '%aLAboutMusic%' OR
					text LIKE '%jakartaPagiini%' OR
					text LIKE '%music%' OR
					text LIKE '%Musisi%' OR
					text LIKE '%RaisaLiveInConcert%' OR
					text LIKE '%concert%' OR
					text LIKE '%BreakTheLimit%' OR
					text LIKE '%MLTR%' OR
					text LIKE '%MLTR Live in Concert%' OR
					text LIKE '%#a7xjakarta%' OR
					text LIKE '%#avengedsevenfoldjkt%' OR
					text LIKE '%#a7xjkt%' OR
					text LIKE '%projam%' OR
					text LIKE '%owlcityjakarta%' OR
					text LIKE 'G-Ground presents%' OR
					text LIKE '%nviozoneTheBeat%' OR
					text LIKE '%DWP2014%' OR
					text LIKE '%PROJAM XTREM Skate%' OR
					text LIKE '%DWP14_Project%' OR
					text LIKE '%djakartawarehouseproject%' OR
					text LIKE '%DWP14%' OR
					text LIKE '%2PM WORLD TOUR%' OR
					text LIKE '%JJF2014%' OR
					text LIKE '%Genre musik%' OR
					text LIKE '%Konser%' OR
					text LIKE '%djakarta warehouse project%' OR
					text LIKE '%pagelaran%' OR
					text LIKE '%panggung%' OR
					text LIKE '%rekaman%' OR
					text LIKE '%album terbaru%' OR
					text LIKE '%java jazz%' OR
					text LIKE '%jazz%' OR
					text LIKE '%#MLDARE2PERFORM%' OR
					text LIKE '%Ngayogjazz%' OR
					text LIKE '%#MLDJazzToRide%'or
					text LIKE '%jjf2016%' OR
					text LIKE '%#MLDJAZZPROJECT%'or
					text LIKE '%#nviozoneJJF16%'or
					text LIKE '%#nviozoneJAZZBUS %'or
					text LIKE '%jjf16%'or
					text LIKE '%#javajazzfestival2016%'or
					text LIKE '%#javajazz2016 %'
				)")->get();
				$temp = array(	
					'category' => 'Music',
					'conversation' => $Qlist->num_rows(),
					'totalFeedback' => 0
				);
				foreach ($Qlist->result() as $row) {
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->where("(
					text LIKE '%otomotif%' OR
					text LIKE '%mobil%' OR
					text LIKE '%motor%' OR
					text LIKE '%modifikasi%' OR
					text LIKE '%modif%' OR
					text LIKE '%BXRides%' OR
					"./*--text LIKE '%bike%' OR*/"
					text LIKE '%BMW%' OR
					text LIKE '%toyota%' OR
					text LIKE '%Honda%' OR
					text LIKE '%Automotive%' OR
					text LIKE '%Nissan%' OR
					text LIKE '%Mazda%' OR
					text LIKE '%Jaguar%' OR
					text LIKE '%iims2015%'
				)")->get();

				$temp = array(	
					'category' => 'Automotive',
					'conversation' => $Qlist->num_rows(),
					'totalFeedback' => 0
				);
				foreach ($Qlist->result() as $row) {
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->where("(
					text LIKE '%Sport%' OR
					text LIKE '%Bola%' OR
					text LIKE '%basket%' OR
					text LIKE '%bike%' OR
					text LIKE '%sepeda%' OR
					text LIKE '%motogp%' OR
					text LIKE '%balapan%' OR
					text LIKE '%Flying Board%' OR
					text LIKE '%MLDare2fly %' OR
					text LIKE '%olahraga%' OR
					text LIKE '%UCL%' OR
					text LIKE '%FIFA%' OR
					text LIKE '%bowling%' OR
					text LIKE '%fun run%' OR
					text LIKE '%COLOR & RUN%' OR
					text LIKE '%color run%' OR
					text LIKE '%bulutangkis%' OR
					text LIKE '%badminton%' OR
					text LIKE '%tenis%' OR
					text LIKE '%tennis%' OR
					text LIKE '%timnas%' OR
					text LIKE '%pertandingan%'  or
					text LIKE '%superbike%' OR
					text LIKE '%f1%'
				)")->get();
				$temp = array(	
					'category' => 'Sport',
					'conversation' => $Qlist->num_rows(),
					'totalFeedback' => 0
				);
				foreach ($Qlist->result() as $row) {
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->where("(
					text LIKE '%Kreatif%' OR
					text LIKE '%Ide Kreatif%' OR
					text LIKE '%creative%' OR
					text LIKE 'seni%' OR
					text LIKE 'art%' OR
					text LIKE '% seni %' OR
					text LIKE '% art %' OR
					text LIKE '%seni' OR
					text LIKE '%art' OR
					text LIKE '%recycle%' OR
					text LIKE '%Komik%' OR
					text LIKE '%comic%' OR
					text LIKE '%Meme%' OR
					text LIKE '%Grafiti%' OR
					text LIKE '%Graffiti%' OR
					text LIKE '%gambar%' OR
					text LIKE '%warna%' OR
					text LIKE '%desain%' OR
					text LIKE '%Kartun%' OR
					text LIKE '%Animasi%' OR
					text LIKE '%LensANatural1st%' OR
					text LIKE '%Natural1st%' OR
					text LIKE '%fotografi%' OR
					text LIKE '%kombinasi warna%' OR
					text LIKE '%interior%' OR
					text LIKE '%eksterior%' 
				)")->get();
				$temp = array(	
					'category' => 'Creative',
					'conversation' => $Qlist->num_rows(),
					'totalFeedback' => 0
				);
				foreach ($Qlist->result() as $row) {
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->where("(
					text LIKE '%inovasi%' OR
					text LIKE '%ide inovasi%' OR
					text LIKE '%terobosan%' OR
					text LIKE '%utak atik%' OR
					text LIKE '%menciptakan inovasi%' OR
					text LIKE '%membuat inovasi%' 
				)")->get();
				$temp = array(	
					'category' => 'Inovative',
					'conversation' => $Qlist->num_rows(),
					'totalFeedback' => 0
				);
				foreach ($Qlist->result() as $row) {
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->where("(
					text LIKE '%Jazz%' OR
					text LIKE '%java jazz%' OR
					text LIKE '%#MLDARE2PERFORM%' OR
					text LIKE '%Ngayogjazz%' OR
					text LIKE '%#MLDJazzToRide%'or
					text LIKE '%jjf2016%' OR
					text LIKE '%#MLDJAZZPROJECT%'or
					text LIKE '%#nviozoneJJF16%'or
					text LIKE '%#nviozoneJAZZBUS %'or
					text LIKE '%jjf16%'or
					text LIKE '%#javajazzfestival2016%'or
					text LIKE '%#javajazz2016%'
				)")->get();
				$temp = array(	
					'category' => 'Jazz',
					'conversation' => $Qlist->num_rows(),
					'totalFeedback' => 0
				);
				foreach ($Qlist->result() as $row) {
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				return $result;
				break;
		}
	}
	public function highlight($account_id, $type, $date)
	{
		switch($type){
			case 'Summary':
				$data = array();
				$period = getmonth('unix', $date);
				$periods = array(date('Y-m', strtotime("-2 month", $period[0])), date('Y-m', strtotime("-1 month", $period[0])), date('Y-m', $period[0]));
				foreach($periods as $period){
					connection_abort();
					$Qlist = $this->db->query("
						SELECT a.*, '$period-01' AS `month`,
							(SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '$period' ORDER BY `created_time` DESC LIMIT 1) AS `fans`,
							(SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$period') AS `posts`,
							(SELECT (SUM(`likes_count`) + SUM(`share_count`) + SUM(`comment_count`)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$period') AS `feedback`
						FROM `account` a
						WHERE a.`account_id`=$account_id
					");
					if($Qlist->num_rows()){
						$temp = json_decode(json_encode($Qlist->row()), true);
						$d = '01-'.date('M-Y', strtotime($period)) . ' ~ ' . date('t-M-Y', strtotime($period));
						$temp['impression'] = $this->highlight($account_id, 'impression', $d);
						$temp['growth'] = $this->report_instagram->highlight($account_id, 'growth_follower', $d);
						$temp['primetime'] = $this->report_instagram->primetime($account_id, 'PrimeTime', $d);
						array_push($data, $temp);
					}
					else $data[]= NULL;
				}
				return $data;
				break;
			case 'growth_follower':
				return $this->growth_follower($account_id, $date);
				break;
		}
	}
	function growth_follower($account_id, $date)
	{
		$period = getmonth('unix', $date);
		$Qlist = $this->db->query("
			SELECT a.`account_id`, a.`name`, 
				IFNULL((SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '".date('Y-m', $period[0])."' ORDER BY `created_time` DESC LIMIT 1), 0) AS `fans`,
				IFNULL((SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '".date('Y-m', strtotime("- 1 month", $period[0]))."' ORDER BY `created_time` DESC LIMIT 1), 0) AS `last_fans`
			FROM `account` a
			WHERE a.`account_id`=$account_id
			ORDER BY `fans` DESC
		");
		if($Qlist->num_rows()){
			$list = $Qlist->row();
			return $list->fans - $list->last_fans;
		}
		else return 0;
	}
	public function hashtag($account_id, $type, $date)
	{
		switch($type){
			case 'MostEngage':
				$period = getmonth('date', $date);
				$Qlist = $this->db->query("
					SELECT COUNT(1) AS `total`, `text`
					FROM `tags`
					WHERE `feed_id` IN(
						SELECT `feed_id` FROM `feed` WHERE `account_id`='$account_id' AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'
					)
					GROUP BY `text`
					ORDER BY `total` DESC
					LIMIT 10
				");
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return array();
				break;
		}
	}
}