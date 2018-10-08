<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Report_twitter extends CI_Model
{
	public function __construct()
	{
        parent::__construct();
    }
	function assuming_friend()
	{
		return 225;
	}
	public function source($id, $where=NULL)
	{
		$query = $this->db->query("select * from account where account_id = '$id'");;
		return $query->row();
	}	
	function competitor($id, $only=false)
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
	public function summary($account_id, $type, $date)
	{
		$period = getmonth('date', $date);
		$competitor = $this->competitor($account_id);
		
		switch($type){
			case 'TopPosting':
				$Qlist = $this->db->query("
					SELECT a.*,
						(SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' ORDER BY `created_time` DESC LIMIT 1) AS `fans`,
						(SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' AND `share_from` IS NULL) AS `posts`,
						(SELECT (SUM(`likes_count`) + SUM(`share_count`)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' AND `share_from` IS NULL) AS `feedback`,
						(SELECT COUNT(1) FROM `mention` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]') AS `reply_mention`
					FROM `account` a
					WHERE a.`account_id` IN($competitor)
					ORDER BY `posts` DESC
				");//mention => AND `share_from` IS NULL
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;
				
			case 'TopFollower':
			case 'TopFollowers':
				if($type == 'TopFollower') $competitor = $this->competitor($account_id, true);
				if($competitor){
					$Qlist = $this->db->query("
						SELECT a.*,
							(SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' ORDER BY `created_time` DESC LIMIT 1) AS `fans`,
							(SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' AND `share_from` IS NULL) AS `posts`,
							(SELECT (SUM(`likes_count`) + SUM(`share_count`)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' AND `share_from` IS NULL) AS `feedback`,
							(SELECT COUNT(1) FROM `mention` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]') AS `reply_mention`
						FROM `account` a
						WHERE a.`account_id` IN($competitor)
						ORDER BY `fans` DESC
					");
					if($Qlist->num_rows()){
						return $Qlist->result();
					}
					else return NULL;
				}
				break;
				
			case 'TopFeedback':
				$Qlist = $this->db->query("
					SELECT a.*,
						(SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' ORDER BY `created_time` DESC LIMIT 1) AS `fans`,
						(SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' AND `share_from` IS NULL) AS `posts`,
						(SELECT (SUM(`likes_count`) + SUM(`share_count`)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' AND `share_from` IS NULL) AS `feedback`,
						(SELECT COUNT(1) FROM `mention` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]') AS `reply_mention`,
						(((SELECT (SUM(`likes_count`) + SUM(`share_count`)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' AND `share_from` IS NULL) + (SELECT COUNT(1) FROM `mention` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]')) / (SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]' AND `share_from` IS NULL)) AS `feedback_rate`
					FROM `account` a
					WHERE a.`account_id` IN($competitor)
					ORDER BY `feedback_rate` DESC
				");
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;
		}
	}
	function posts($account_id, $date, $where=array())
	{
		$period = getmonth('date', $date);
		$Qlist = $this->db->select('feed_id')->from('feed')->where(array_merge($where, array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL,  "share_from IS NULL" => NULL)))->get();
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
	function growth_daily($account_id, $date)
	{
		$period = getmonth('date', $date);
		$Qlist = $this->db->from('follower')->where(array('account_id' => $account_id, "DATE_FORMAT(created_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL))->order_by('created_time', 'ASC')->get();
		if($Qlist->num_rows()){			
			$Qprev = $this->db->from('follower')->where(array('account_id' => $account_id, "DATE_FORMAT(created_time, '%Y-%m-%d')=" => date('Y-m-d', strtotime("-1 days", strtotime($period[0])))))->get();
			if($Qprev->num_rows()){
				$prev = $Qprev->row()->count;
			}
			else $prev = 0;			
			foreach($Qlist->result() as $list){
				$data[] = array(
					'date' => $list->created_time,
					'count' => $list->count,
					'growth' => ($list->count - $prev)
				);
				$prev = $list->count;
			}
		}
		else $data = NULL;		
		return $data;
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
							(SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$period' AND `share_from` IS NULL) AS `posts`,
							(SELECT (SUM(`likes_count`) + SUM(`share_count`)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$period' AND `share_from` IS NULL) AS `feedback`,
							(SELECT COUNT(1) FROM `mention` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$period') AS `reply_mention`
						FROM `account` a
						WHERE a.`account_id`=$account_id
					");
					if($Qlist->num_rows()){
						$temp = json_decode(json_encode($Qlist->row()), true);
						$d = '01-'.date('M-Y', strtotime($period)) . ' ~ ' . date('t-M-Y', strtotime($period));
						$temp['impression'] = intval($this->highlight($account_id, 'impression', $d)) + intval($temp['fans']);
						$temp['growth'] = $this->highlight($account_id, 'growth_follower', $d);
						$temp['primetime'] = $this->primetime($account_id, 'PrimeTime', $d);
						array_push($data, $temp);
					}
					else $data[]= NULL;
				}
				return $data;
				break;
				
			case 'Growth':
				$period = getmonth('unix', $date);
				$prev_month = array(strtotime("-2 month", $period[0]), strtotime("-1 month", $period[0]));
				$current_growth = $this->growth_follower($account_id, $date);				
				if($growthdaily = $this->growth_daily($account_id, $date)){
					$sortArray = sort_array($growthdaily);
					array_multisort($sortArray['growth'], SORT_DESC, $growthdaily);
					$growth = date('d F', strtotime($growthdaily[0]['date'])).' ('.number_format((($growthdaily[0]['growth'] / ($current_growth ? $current_growth : 1)) * 100), 2).'% / '.number_format($growthdaily[0]['growth']).' Followers)';
				}				
				return isset($growth) ? $growth : '-';
				break;				
			case 'impression':
				$period = getmonth('unix', $date);
				$from = date('Y-m', $period[0]);
				$to = date('Y-m', $period[1]);				
				$rawdata = 'rawdata/'.(($from == $to) ? $to : 'x').'/'.$account_id.'/impression_count.json';
				if(file_exists($rawdata)){
					$data = file_get_contents(base_url($rawdata));
					if($data){
						return $data;
					}
					else return 0;
					
				}else{					
					return 0;
				}
				break;				
			case 'growth_follower':
				return $this->growth_follower($account_id, $date);
				break;
		}
	}
	public function activities($account_id, $type, $date)
	{
		$period = getmonth('date', $date);		
		switch($type){
			case 'AccountPost':
				$Qlist = $this->db->select('COUNT(1) AS total, type')->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "share_from IS NULL" => NULL))->group_by('type')->order_by('type', 'ASC')->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'TotalFeedback':
				$Qlist = $this->db->select('(IFNULL(SUM(likes_count), 0) + IFNULL(SUM(share_count), 0) + IFNULL(SUM(comment_count), 0)) AS total, type')->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL,  "share_from IS NULL" => NULL))->group_by('type')->order_by('type', 'ASC')->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'TotalAmplification':
				if(in_array($account_id, exc_account('n3o'), true)){
					if($Qtype = $this->activities($account_id, 'AccountPost', $date)){
						foreach($Qtype as $type){
							connection_abort();
							$total = 0;
							$feeds = $this->posts($account_id, $date, array('type' => $type->type));
							if($feeds <> "'0'"){
								$total = $this->impression($feeds, $date);
							}
							$data[] = array('type' => $type->type, 'total' => $total);
						}
					}
					else $data = array();					
					return json_decode(json_encode($data));					
				}else{					
					$period_start = strtotime($period[0]);
					$period_end = strtotime($period[1]);					
					$from = date('Y-m', $period_start);
					$to = date('Y-m', $period_end);					
					if($from == $to){						
						if(date('d', $period_start) <> '01') return NULL;
						if(date('d', $period_end) <> date('t', $period_start)) return NULL;						
						$rawdata = 'rawdata/'.$to.'/'.$account_id.'/amplification_count.json';
						if(file_exists($rawdata)){
							$data = file_get_contents(base_url($rawdata));
							if($json = json_decode($data)){
								return $json;
							}
							else return NULL;
						}
						else return NULL;
						
					}
					else return NULL;
				}
				break;
		}
	}
	public function users($account_id, $type, $date)
	{
		$period = getmonth('unix', $date);
		$from = date('Y-m', $period[0]);
		$to = date('Y-m', $period[1]);
		switch($type){
			case 'MostMention':
				$rawdata = 'rawdata/'.(($from == $to) ? $to : 'x').'/'.$account_id.'/most_mention.json';
				if(file_exists($rawdata)){
					$data = file_get_contents(base_url($rawdata));
					if($json = json_decode($data)){
						return $json;
					}
					else return NULL;					
				}else{					
					if(in_array($account_id, exc_account('n3o'), true)){
						$feeds = $this->posts($account_id, $date);
						$Qlist = $this->db->select("COUNT(1) AS t, b.socmed_id AS s, b.username AS u, b.name AS n, b.photo_profile AS p, IFNULL(b.city, b.location) AS l, b.follower AS f")->from('mention a')->join('user b', 'a.user_id=b.user_id')->where(array("a.feed_id IN($feeds)" => NULL))->group_by('a.user_id')->order_by('t', 'DESC')->limit(10)->get();
						if($Qlist->num_rows()){
							return $Qlist->result();
						}
						else return NULL;
					}
					else return NULL;
				}
				break;				
			case 'MostShare':
				$rawdata = 'rawdata/'.(($from == $to) ? $to : 'x').'/'.$account_id.'/most_share.json';
				if(file_exists($rawdata)){
					$data = file_get_contents(base_url($rawdata));
					if($json = json_decode($data)){
						return $json;
					}
					else return NULL;					
				}else{					
					if(in_array($account_id, exc_account('n3o'), true)){
							$feeds = $this->posts($account_id, $date);
							$Qlist = $this->db->select("COUNT(1) AS t, b.socmed_id AS s, b.username AS u, b.name AS n, b.photo_profile AS p, IFNULL(b.city, b.location) AS l, b.follower AS f")->from('share a')->join('user b', 'a.user_id=b.user_id')->where(array("a.feed_id IN($feeds)" => NULL))->group_by('a.user_id')->order_by('t', 'DESC')->limit(10)->get();
							if($Qlist->num_rows()){
								return $Qlist->result();
							}
							else return NULL;
					}
					else return NULL;
				}
				break;				
			case 'MostActiveCount':
				$rawdata = 'rawdata/'.(($from == $to) ? $to : 'x').'/'.$account_id.'/most_active-count.json';
				if(file_exists($rawdata)){
					$data = file_get_contents(base_url($rawdata));
					if($data){
						return $data;
					}
					else return 0;					
				}else{					
					if(in_array($account_id, exc_account('n3o'), true)){
						$feeds = $this->posts($account_id, $date);
						return $this->db->query("
							SELECT x.`user_id` FROM (
								SELECT `user_id` FROM `comment` WHERE `feed_id` IN($feeds) GROUP BY `user_id`
								UNION ALL
								SELECT `user_id` FROM `likes` WHERE `feed_id` IN($feeds) GROUP BY `user_id`
								UNION ALL
								SELECT `user_id` FROM `share` WHERE `feed_id` IN($feeds) GROUP BY `user_id`
							) AS x
							GROUP BY x.`user_id`
						")->num_rows();
					}
					else return 0;
				}
				break;				
			case 'MostActive':
				$rawdata = 'rawdata/'.(($from == $to) ? $to : 'x').'/'.$account_id.'/most_active.json';
				if(file_exists($rawdata)){
					$data = file_get_contents(base_url($rawdata));
					if($json = json_decode($data)){
						return $json;
					}
					else return NULL;					
				}else{					
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
					}
					else return NULL;
				}
				break;				
			case 'TopSpeakers':
				$rawdata = 'rawdata/'.(($from == $to) ? $to : 'x').'/'.$account_id.'/top_speakers.json';
				if(file_exists($rawdata)){
					$data = file_get_contents(base_url($rawdata));
					if($json = json_decode($data)){
						return $json;
					}
					else return NULL;					
				}else{					
					if(in_array($account_id, exc_account('n3o'), true)){
						$feeds = $this->posts($account_id, $date);
						$Qlist = $this->db->query("
							SELECT SUM(x.`activity`) AS `t`, (SUM(x.`activity`) * (CASE IFNULL(b.`follower`, 0) WHEN 0 THEN 1 ELSE IFNULL(b.`follower`, 0) END)) AS `i`, x.`user_id`, b.`socmed_id` AS `s`, b.`username` AS `u`, b.`name` AS `n`, b.`photo_profile` AS `p`, IFNULL(b.`city`, b.`location`) AS `l`, b.`follower` AS `f` FROM (
								SELECT COUNT(1) AS `activity`, `user_id` FROM `comment` WHERE `feed_id` IN($feeds) GROUP BY `user_id`
								UNION ALL
								SELECT COUNT(1) AS `activity`, `user_id` FROM `likes` WHERE `feed_id` IN($feeds) GROUP BY `user_id`
								UNION ALL
								SELECT COUNT(1) AS `activity`, `user_id` FROM `share` WHERE `feed_id` IN($feeds) GROUP BY `user_id`
							) AS x
							JOIN `user` b ON x.`user_id`=b.`user_id`
							GROUP BY b.`socmed_id`
							ORDER BY `i` DESC LIMIT 10
						");
						if($Qlist->num_rows()){
							return $Qlist->result();
						}
						else return NULL;
					}
					else return NULL;
				}
				break;
		}
	}
	public function performance($account_id, $type, $date)
	{
		switch($type){
			case 'Summary':
				$month = date('Y-m', $date);
				$last_month = date('Y-m', strtotime("- 1 month", $date));
				$competitor = $this->competitor($account_id);
				$Qlist = $this->db->query("
					SELECT a.`account_id`, a.`name`, a.`username`, a.`socmed_id`,
						IFNULL((SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '$month' ORDER BY `created_time` DESC LIMIT 1), 0) AS `fans`,
						IFNULL((SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '$last_month' ORDER BY `created_time` DESC LIMIT 1), 0) AS `last_fans`,
						IFNULL((SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$month' AND `share_from` IS NULL), 0) AS `post`,
						IFNULL((SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$last_month' AND `share_from` IS NULL), 0) AS `last_post`,
						IFNULL((SELECT (SUM(`likes_count`) + SUM(`share_count`)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$month' AND `share_from` IS NULL), 0) AS `feedback`,
						IFNULL((SELECT (SUM(`likes_count`) + SUM(`share_count`)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$last_month' AND `share_from` IS NULL), 0) AS `last_feedback`,
						IFNULL((SELECT COUNT(1) FROM `mention` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$month'), 0) AS `reply_mention`,
						IFNULL((SELECT COUNT(1) FROM `mention` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$last_month'), 0) AS `last_reply_mention`
					FROM `account` a
					WHERE a.`account_id` IN($competitor)
					ORDER BY `fans` DESC
				");
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'VsCompetitor':
				$Qlist = $this->db->from('follower')->where(array('account_id' => $account_id, "DATE_FORMAT(created_time, '%Y-%m') =" => $date))->order_by('created_time', 'DESC')->limit(1)->get();
				if($Qlist->num_rows()){
					return $Qlist->row()->count;
				}
				else return 0;
				break;				
			case 'Interaction':
				$period = getmonth('unix', $date);
				$month = date('Y-m', $period[0]);
				$competitor = $this->competitor($account_id);
				$Qlist = $this->db->query("
					SELECT a.`account_id`, a.`name`, a.`username`, a.`socmed_id`,
						IFNULL((SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '$month' ORDER BY `created_time` DESC LIMIT 1), 0) AS `fans`,
						IFNULL((SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$month' AND `share_from` IS NULL), 0) AS `post`,
						IFNULL((SELECT (SUM(`likes_count`) + SUM(`share_count`)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$month' AND `share_from` IS NULL), 0) AS `feedback`,
						IFNULL((SELECT COUNT(1) FROM `mention` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '$month'), 0) AS `reply_mention`
					FROM `account` a
					WHERE a.`account_id` IN($competitor)
					ORDER BY `feedback` DESC
				");
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'competitor':
				$Qlist = $this->db->select('b.*')->from('account_competitor a')->join('account b', 'a.account_id=b.account_id')->where(array('a.main_account' => $account_id))->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;
		}
	}
	public function primetime($account_id, $type, $date)
	{
		switch($type){
			case 'FeedIds':
				return $this->posts($account_id, $date);
				break;				
			case 'DailyPost':
				$period = getmonth('date', $date[0]);
				return $this->db->select('feed_id')->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "DATE_FORMAT(post_time, '%H/%w') =" => $date[1],  "share_from IS NULL" => NULL))->get()->num_rows();
				break;				
			case 'SumPost':
				$period = getmonth('date', $date[0]);
				return $this->db->select('feed_id')->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "DATE_FORMAT(post_time, '$date[1]') =" => $date[2],  "share_from IS NULL" => NULL))->get()->num_rows();
				break;				
			case 'DailyComment':
				$feeds = $this->posts($account_id, $date[0]);
				return $this->db->select('mention_id')->from('mention')->where(array("feed_id IN($feeds)" => NULL, "DATE_FORMAT(post_time, '%H/%w') =" => $date[1]))->get()->num_rows();
				break;				
			case 'DailyCommentx':
				return $this->db->query("SELECT `mention_id` FROM `mention` WHERE `feed_id` IN($date[0]) AND DATE_FORMAT(`post_time`, '%H/%w') = '$date[1]'")->num_rows();
				break;				
			case 'SumComment':
				$feeds = $this->posts($account_id, $date[0]);
				return $this->db->select('mention_id')->from('mention')->where(array("feed_id IN($feeds)" => NULL, "DATE_FORMAT(post_time, '$date[1]') =" => $date[2]))->get()->num_rows();
				break;				
			case 'SumCommentx':
				return $this->db->query("SELECT `mention_id` FROM `mention` WHERE `feed_id` IN($date[0]) AND DATE_FORMAT(`post_time`, '$date[1]') = '$date[2]'")->num_rows();
				break;				
			case 'HourComment':
				$feeds = $this->posts($account_id, $date);
				$Qlist = $this->db->query("
					SELECT SUM(x.`activity`) AS `total`, x.`hour` FROM (
						SELECT COUNT(1) AS `activity`, DATE_FORMAT(`post_time`, '%H') AS `hour` FROM `mention` WHERE `feed_id` IN($feeds) GROUP BY DATE_FORMAT(`post_time`, '%H')
						UNION ALL
						SELECT COUNT(1) AS `activity`, DATE_FORMAT(`created_time`, '%H') AS `hour` FROM `share` WHERE `feed_id` IN($feeds) GROUP BY DATE_FORMAT(`created_time`, '%H')
					) AS x
					GROUP BY `hour`
					ORDER BY `hour` ASC
				");
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL	;
				break;				
			case 'DailyShare':
				$feeds = $this->posts($account_id, $date[0]);
				return $this->db->select('share_id')->from('share')->where(array("feed_id IN($feeds)" => NULL, "DATE_FORMAT(created_time, '%H/%w') =" => $date[1]))->get()->num_rows();
				break;				
			case 'DailySharex':
				return $this->db->query("SELECT `share_id` FROM `share` WHERE `feed_id` IN($date[0]) AND DATE_FORMAT(`created_time`, '%H/%w') = '$date[1]'")->num_rows();
				break;				
			case 'SumShare':
				$feeds = $this->posts($account_id, $date[0]);
				return $this->db->select('share_id')->from('share')->where(array("feed_id IN($feeds)" => NULL, "DATE_FORMAT(created_time, '$date[1]') =" => $date[2]))->get()->num_rows();
				break;				
			case 'SumSharex':
				return $this->db->query("SELECT `share_id` FROM `share` WHERE `feed_id` IN($date[0]) AND DATE_FORMAT(`created_time`, '$date[1]') = '$date[2]'")->num_rows();
				break;				
			case 'PrimeTime':
				$feeds = $this->posts($account_id, $date);
				$Qlist = $this->db->query("
					SELECT SUM(x.`activity`) AS `total`, x.`hour` FROM (
						SELECT COUNT(1) AS `activity`, DATE_FORMAT(`post_time`, '%H') AS `hour` FROM `mention` WHERE `feed_id` IN($feeds) GROUP BY DATE_FORMAT(`post_time`, '%H')
						UNION ALL
						SELECT COUNT(1) AS `activity`, DATE_FORMAT(`created_time`, '%H') AS `hour` FROM `share` WHERE `feed_id` IN($feeds) GROUP BY DATE_FORMAT(`created_time`, '%H')
					) AS x
					GROUP BY `hour`
					ORDER BY `total` DESC
					LIMIT 1
				");
				if($Qlist->num_rows()){
					return $Qlist->row();
				}
				else return NULL	;
				break;
		}
	}
	public function growth($account_id, $type, $date)
	{
		switch($type){
			case 'impression':
				break;				
			case 'CountPost':
				return $this->db->select('feed_id')->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '$date[0]') =" => $date[1], "share_from IS NULL" => NULL))->get()->num_rows();
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
	public function benchmark($account_id, $type, $date)
	{
		if($date){
			$period = getmonth('date', $date);
		}		
		switch($type){
			case 'Category':
				$Qlist = $this->db->select('category')->from('account')->where(array('category IS NOT NULL' => NULL, 'socmed' => 'twitter', 'fl_active' => 1))->group_by('category')->order_by('category', 'ASC')->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			default:
				$Qacc = $this->db->query("
					SELECT a.`account_id`, a.`socmed_id`, a.`username`, a.`name`,
						(SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '".date('Y-m', strtotime($period[0]))."' ORDER BY `created_time` DESC LIMIT 1) AS `followers`,
						(SELECT COUNT(1) FROM `feed` WHERE `share_from` IS NULL AND `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '".date('Y-m', strtotime($period[0]))."') AS `posts`,
						(SELECT (IFNULL(SUM(likes_count), 0) + IFNULL(SUM(share_count), 0)) FROM `feed` WHERE `share_from` IS NULL AND `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '".date('Y-m', strtotime($period[0]))."') AS `feedback`,
						(SELECT COUNT(1) FROM `mention` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '".date('Y-m', strtotime($period[0]))."') AS `reply_mention`,
						((SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '".date('Y-m', strtotime($period[0]))."' ORDER BY `created_time` DESC LIMIT 1) - (SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '".date('Y-m', strtotime("-1 month", strtotime($period[0])))."' ORDER BY `created_time` DESC LIMIT 1)) AS `growth`
					FROM `account` a
					WHERE a.`category`='$type' AND a.`socmed`='twitter' AND a.`fl_active`=1
					ORDER BY `growth` DESC
				");
				$accs = '';
				$list_acc = '';
				$num_users = 0;
				$growth_cat = 0;
				$mp_cat = 0;
				$feedbacks = 0;
				$ecom_cat = 0;
				$active_cat = 0;
				$num_acc = $Qacc->num_rows();
				foreach($Qacc->result() as $ls){
					$accs .= $accs ? ','.$ls->account_id : $ls->account_id;
					$active_user = $this->users($ls->account_id, 'MostActiveCount', $date);
					$impression = $this->highlight($ls->account_id, 'impression', $date) + $ls->followers;		
					$growth_brand = $ls->growth / ($ls->followers ? $ls->followers : 1);
					$growth_cat = $growth_brand + $growth_cat;					
					$mp_brand = $ls->growth / ($impression ? $impression : 1);
					$mp_cat = $mp_brand + $mp_cat;					
					$active_brand = $active_user / ($ls->followers ? $ls->followers : 1);
					$active_cat = $active_brand + $active_cat;					
					$feedback = $ls->feedback + $ls->reply_mention;
					$feedbacks = $feedback + $feedbacks;					
					$feedback_rate = $feedback / ($ls->posts ? $ls->posts : 1);
					$ecom_brand = $feedback_rate / ($ls->followers ? $ls->followers : 1);
					$ecom_cat = $ecom_brand + $ecom_cat;					
					$num_users = $active_user + $num_users;
					$list_acc .= '<a href="'.($ls->username ? 'https://twitter.com/'.$ls->username : 'https://twitter.com/intent/user?user_id='.$ls->socmed_id).'" target="_blank" title="'.$ls->account_id.'">'.$ls->name.'</a><br>';
				}				
				return array(
					'growth' => number_format(($growth_cat / $num_acc) * 100, 2).'%',
					'market_penetration' => number_format(($mp_cat / $num_acc) * 100, 2).'%',
					'engagement_ratio' => number_format(($active_cat / $num_acc) * 100, 2).'%',
					'effective_communication' => number_format(($ecom_cat / $num_acc) * 100, 2).'%',
					'conversations' => number_format($feedbacks),
					'users_active' => number_format($num_users),
					'num_accounts' => $num_acc,
					'list_accounts' => $list_acc
				);
				break;
		}
	}
	public function effective_communication($account_id, $type, $date)
	{
		if($date){
			$period = getmonth('date', $date);
		}		
		$Qacc = $this->db->query("
			SELECT a.`account_id`, a.`socmed_id`, a.`username`, a.`name`,
				(SELECT `count` FROM `follower` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`created_time`, '%Y-%m') = '".date('Y-m', strtotime("-1 month", strtotime($period[0])))."' ORDER BY `created_time` DESC LIMIT 1) AS `followers`,
				(SELECT COUNT(1) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '".date('Y-m', strtotime($period[0]))."') AS `posts`,
				(SELECT (IFNULL(SUM(likes_count), 0) + IFNULL(SUM(share_count), 0) + IFNULL(SUM(comment_count), 0)) FROM `feed` WHERE `account_id`=a.`account_id` AND DATE_FORMAT(`post_time`, '%Y-%m') = '".date('Y-m', strtotime($period[0]))."') AS `feedback`
			FROM `account` a
			WHERE a.`category`='$type' AND a.`socmed`='twitter' AND a.`fl_active`=1
		");
		$ecom_cat = 0;
		$num_acc = $Qacc->num_rows();
		$data['data'] = array();
		foreach($Qacc->result() as $ls){
			$temp = array();
			$temp['account_id'] = $ls->account_id;
			$temp['name'] = $ls->name;
			$temp['posts'] = $ls->posts;
			$temp['feedback'] = $ls->feedback;
			$temp['feedback_rate'] = round($temp['feedback'] / ($temp['posts'] ? $temp['posts'] : 1));
			$temp['fans'] = $ls->followers;
			$temp['effective_communication'] = round($temp['feedback_rate'] / ($temp['fans'] ? $temp['fans'] : 1) * 100, 2) . "%";
			array_push($data['data'], $temp);
		}
		return $data;
	}
	public function feeds($account_id, $type, $date)
	{
		$period = getmonth('date', $date);		
		switch($type){
			case 'AllAccount':
				$competitor = $this->competitor($account_id);
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time, b.name, b.socmed_id, b.username")->from('feed a')->join('account b', 'a.account_id = b.account_id')->where(array("a.account_id IN($competitor)" => NULL, "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('total', 'DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;			
			case 'Category':
				$competitor = $this->competitor($account_id->account_id);
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time, b.name, b.socmed_id, b.username")->from('feed a')->join('account b', "a.account_id = b.account_id AND b.category = '$account_id->category'")->where(array("a.account_id IN($competitor)" => NULL, "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('total', 'DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'AllTopPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('total DESC, a.post_time DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'AllLeastPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('total ASC, a.post_time ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'NotLinkTopPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, 'a.type <>' => 'link', "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('total DESC, a.post_time DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'NotLinkLeastPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, 'a.type <>' => 'link', "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, 'post_id NOT IN("820858312941912065", "820963825851711489")' => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('total ASC, a.post_time ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'LinkTopPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, 'a.type' => 'link', "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('total DESC, a.post_time DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'LinkLeastPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, 'a.type' => 'link', "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('total ASC, a.post_time ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'ShareTopPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, 'a.share_count > 0' => NULL, "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('a.share_count DESC, a.post_time DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'ShareLeastPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, 'a.share_count > 0' => NULL, "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('a.share_count ASC, a.post_time ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'LikeTopPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, 'a.likes_count > 0' => NULL, "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('a.likes_count DESC, a.post_time DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'LikeLeastPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, 'a.likes_count > 0' => NULL, "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('a.likes_count ASC, a.post_time ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'CommentTopPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('comment_count DESC, a.post_time DESC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;				
			case 'CommentLeastPost':
				$Qlist = $this->db->select("(a.likes_count + (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) + a.share_count) AS total, a.likes_count, (SELECT COUNT(1) FROM mention WHERE feed_id=a.feed_id) AS comment_count, a.share_count, a.est_impression, a.post_id, a.image_url, a.video_url, a.text, a.permalink, a.post_time")->from('feed a')->where(array('a.account_id' => $account_id, "DATE_FORMAT(a.post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, "a.share_from IS NULL" => NULL))->group_by('a.post_id')->order_by('comment_count ASC, a.post_time ASC')->limit(5)->get();
				if($Qlist->num_rows()){
					return $Qlist->result();
				}
				else return NULL;
				break;
		}
	}
	public function conversation($account_id, $type, $date)
	{
		$period = getmonth('date', $date);
		
		switch($type){
			case 'All':
				$result = array();
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, 'share_from IS NULL' => NULL))->where("(
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
					'totalFeedback' => 0);
				foreach ($Qlist->result() as $row) { 					
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, 'share_from IS NULL' => NULL))->where("(
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
					'totalFeedback' => 0);
				foreach ($Qlist->result() as $row) { 					
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, 'share_from IS NULL' => NULL))->where("(
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
					'totalFeedback' => 0);
				foreach ($Qlist->result() as $row) { 					
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, 'share_from IS NULL' => NULL))->where("(
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
					'totalFeedback' => 0);
				foreach ($Qlist->result() as $row) { 					
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, 'share_from IS NULL' => NULL))->where("(
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
					'totalFeedback' => 0);
				foreach ($Qlist->result() as $row) { 					
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, 'share_from IS NULL' => NULL))->where("(
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
					'totalFeedback' => 0);
				foreach ($Qlist->result() as $row) { 					
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, 'share_from IS NULL' => NULL))->where("(
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
					'totalFeedback' => 0);
				foreach ($Qlist->result() as $row) { 					
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, 'share_from IS NULL' => NULL))->where("(
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
					'totalFeedback' => 0);
				foreach ($Qlist->result() as $row) { 					
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);				
				$Qlist = $this->db->from('feed')->where(array('account_id' => $account_id, "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '$period[0]' AND '$period[1]'" => NULL, 'share_from IS NULL' => NULL))->where("(
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
					'totalFeedback' => 0);
				foreach ($Qlist->result() as $row) { 					
					$temp['totalFeedback'] += $row->likes_count + $row->share_count + $row->comment_count;
				}
				array_push($result, $temp);
				return $result;
				break;
		}
	}
}
?>