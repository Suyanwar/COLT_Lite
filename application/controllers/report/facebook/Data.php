<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('report_facebook');
	}
	public function source($id)
	{
		$Qacc = $this->db->query("SELECT * FROM `account` 
			WHERE `socmed` = 'facebook' 
			AND `account_id` = " . $id
		);
		return $Qacc->result();
	}
	public function highlight($id)
	{
		$temp = $this->source($id);
		$data['account'] = $temp[0];
		$date = $_POST['date'];
		$data['date'] = $date;
		$data['highlight'] = $this->report_facebook->highlight($id, 'Summary', $date);
		$data['growth'] = $this->report_facebook->highlight($id, 'Growth', $date);
		$data['benchmark'] = $this->report_facebook->benchmark($id, $data['account']->category, $date);
		$data['AllAccount'] = $this->report_facebook->feeds($id, 'AllTopPost', $date);
		$this->load->view('report/facebook/data/Highlight', $data);
	}
	public function feeds($id)
	{
		$date = $_POST['date'];
		$data['AllAccount'] = $this->report_facebook->feeds($id, 'AllAccount', $date);
		$data['Category'] = $this->report_facebook->feeds($this->report_facebook->source($id), 'Category', $date);
		$data['AllTopPost'] = $this->report_facebook->feeds($id, 'AllTopPost', $date);
		$data['AllLeastPost'] = $this->report_facebook->feeds($id, 'AllLeastPost', $date);
		$data['NotLinkTopPost'] = $this->report_facebook->feeds($id, 'NotLinkTopPost', $date);
		$data['NotLinkLeastPost'] = $this->report_facebook->feeds($id, 'NotLinkLeastPost', $date);
		$data['LinkTopPost'] = $this->report_facebook->feeds($id, 'LinkTopPost', $date);
		$data['LinkLeastPost'] = $this->report_facebook->feeds($id, 'LinkLeastPost', $date);
		$data['ShareTopPost'] = $this->report_facebook->feeds($id, 'ShareTopPost', $date);
		$data['ShareLeastPost'] = $this->report_facebook->feeds($id, 'ShareLeastPost', $date);
		$data['LikeTopPost'] = $this->report_facebook->feeds($id, 'LikeTopPost', $date);
		$data['LikeLeastPost'] = $this->report_facebook->feeds($id, 'LikeLeastPost', $date);
		$data['CommentTopPost'] = $this->report_facebook->feeds($id, 'CommentTopPost', $date);
		$data['CommentLeastPost'] = $this->report_facebook->feeds($id, 'CommentLeastPost', $date);
		$data['BoostTopPost'] = $this->report_facebook->feeds($id, 'BoostTopPost', $date);
		$data['BoostLeastPost'] = $this->report_facebook->feeds($id, 'BoostLeastPost', $date);
		$data['OrganicTopPost'] = $this->report_facebook->feeds($id, 'OrganicTopPost', $date);
		$data['OrganicLeastPost'] = $this->report_facebook->feeds($id, 'OrganicLeastPost', $date);
		$data['AllTopVideoPost'] = $this->report_facebook->feeds($id, 'AllTopVideoPost', $date);
		$data['AllLeastVideoPost'] = $this->report_facebook->feeds($id, 'AllLeastVideoPost', $date);
		$data['account'] = $id;
		$data['date'] = $date;
		$this->load->view('report/facebook/data/Feeds', $data);
	}
	public function activity($id){
		$date = $_POST['date'];
		$data['AccountPost'] = $this->report_facebook->activities($id, 'AccountPost', $date);
		$data['TotalFeedback'] = $this->report_facebook->activities($id, 'TotalFeedback', $date);
		$data['TotalAmplification'] = $this->report_facebook->activities($id, 'TotalAmplification', $date);
		$data['account'] = $id;
		$data['date'] = $date;
		$this->load->view('report/facebook/data/Activities', $data);
	}
	public function benchmark($id){
		$temp = $this->source($id);
		$data['account'] = $temp[0];
		$date = $_POST['date'];
		$data['date'] = $date;
		$data['unix'] = getmonth('unix', $date);
		$data['competitors'] = $this->report_facebook->performance($id, 'competitor', NULL);
		$data['data'] = $this->report_facebook->performance($id, 'Interaction', $date);
		$this->load->view('report/facebook/data/Benchmark', $data);
	}
	public function users($id)
	{
		$date = $_POST['date'];
		$data['account'] = $id;
		$data['date'] = $date;
		$data['top_speakers'] = $this->report_facebook->users($id, 'TopSpeakers', $date);
		$data['most_active'] = $this->report_facebook->users($id, 'MostActive', $date);
		$data['most_commenters'] = $this->report_facebook->users($id, 'MostCommenters', $date);
		$data['most_likers'] = $this->report_facebook->users($id, 'MostLikers', $date);
		$this->load->view('report/facebook/data/Users', $data);
	}
	public function performance($id)
	{
		$temp = $this->source($id);
		$data['account'] = $temp[0];
		$date = $_POST['date'];
		$data['fdate'] = $date;
		$unix = getmonth('unix', $date);
		$date = getmonth('date', $date);
		$data['date'] = $date[0];
		$data['unix'] = $unix[0];
		$data['competitors'] = $this->report_facebook->performance($id, 'competitor', NULL);
		$data['data'] = $this->report_facebook->performance($id, 'Summary', $unix[0]);
		$data['interaction'] = $this->report_facebook->performance($id, 'Interaction', $data['fdate']);
		$this->load->view('report/facebook/data/Performance', $data);
	}
	public function effective_communication($id)
	{
		$temp = $this->source($id);
		$data['account'] = $temp[0];
		$date = $_POST['date'];
		$temp = $this->report_facebook->effective_communication($id, $data['account']->category, $date);
		$data['data'] = $temp['data'];
		$data['date'] = $date;
		$this->load->view('report/facebook/data/Effective_Communication', $data);
	}
	public function primetime($id)
	{
		$data['account'] = $id;
		$data['date'] = $_POST['date'];
		$data['unix'] = getmonth('unix', $data['date']);
		$this->load->view('report/facebook/data/PrimeTime', $data);
	}
	public function conversation($id)
	{
		$date = $_POST['date'];
		$data['account'] = $id;
		$data['data'] = $this->report_facebook->conversation($id, 'All', $date);	
		$data['date'] = $date;	
		$this->load->view('report/facebook/data/Conversation', $data);
	}
	public function growth($id){
		$date = $_POST['date'];
		$temp = $this->report_facebook->highlight($id, 'Summary', $date);
		$data['account'] = array('account_id' => $id, 'name' => $temp[0]['name']);
		$data['date'] = $date;
		$data['unix'] = getmonth('unix', $date);
		$data['dailyFans'] = $this->report_facebook->growth($id, 'DailyFans', $date);
		$data['dailyGrowth'] = $this->report_facebook->growth($id, 'DailyFans', $date);
		$data['monthlyGrowthCompetitor'] = $this->report_facebook->summary($id, 'TopFollower', lastmonth());
		$data['data'] = $this->report_facebook->performance($id, 'Interaction', $date);
		$this->load->view('report/facebook/data/Growth', $data);
	}
	public function fans_connect($id)
	{
		$date = $_POST['date'];
		$data['date'] = $date;
		$data['data'] = $this->report_facebook->linking($id, 'TopFanpage', $date);
		$data['account'] = $id;
		$this->load->view('report/facebook/data/Fans_Connect', $data);
	}
}