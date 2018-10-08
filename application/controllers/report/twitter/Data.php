<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('report_twitter');
	}
	public function source($id)
	{
		$Qacc = $this->db->query("SELECT * FROM `account` 
			WHERE `socmed` = 'twitter' 
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
		$data['highlight'] = $this->report_twitter->highlight($id, 'Summary', $date);
		$data['growth'] = $this->report_twitter->highlight($id, 'Growth', $date);
		$data['benchmark'] = $this->report_twitter->benchmark($id, $data['account']->category, $date);
		$data['AllAccount'] = $this->report_twitter->feeds($id, 'AllAccount', $date);
		$this->load->view('report/twitter/data/Highlight', $data);
	}
	public function benchmark($id)
	{
		$temp = $this->source($id);
		$data['account'] = $temp[0];
		$date = $_POST['date'];
		$data['date'] = $date;
		$data['unix'] = getmonth('unix', $date);
		$temp = $this->report_twitter->highlight($id, 'Summary', $date);
		$data['competitors'] = $this->report_twitter->performance($id, 'competitor', NULL);
		$data['data'] = $this->report_twitter->performance($id, 'Interaction', $date);
		$this->load->view('report/twitter/data/Benchmark', $data);
	}
	public function effective_communication($id)
	{
		$temp = $this->source($id);
		$data['account'] = $temp[0];
		$date = $_POST['date'];
		$temp = $this->report_twitter->effective_communication($id, $data['account']->category, $date);
		$data['data'] = $temp['data'];
		$data['date'] = $date;
		$this->load->view('report/twitter/data/Effective_Communication', $data);
	}
	public function users($id)
	{
		$date = $_POST['date'];
		$data['account'] = $id;
		$data['date'] = $date;
		$data['top_speakers'] = $this->report_twitter->users($id, 'TopSpeakers', $date);
		$data['most_active'] = $this->report_twitter->users($id, 'MostActive', $date);
		$data['most_commenters'] = $this->report_twitter->users($id, 'MostShare', $date);
		$data['most_likers'] = $this->report_twitter->users($id, 'MostMention', $date);
		$this->load->view('report/twitter/data/Users', $data);
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
		$data['competitors'] = $this->report_twitter->performance($id, 'competitor', NULL);
		$data['data'] = $this->report_twitter->performance($id, 'Summary', $unix[0]);
		$data['interaction'] = $this->report_twitter->performance($id, 'Interaction', $data['fdate']);
		$this->load->view('report/twitter/data/Performance', $data);
	}
	public function activity($id){
		$date = $_POST['date'];
		$data['date'] = $date;
		$data['AccountPost'] = $this->report_twitter->activities($id, 'AccountPost', $date);
		$data['TotalFeedback'] = $this->report_twitter->activities($id, 'TotalFeedback', $date);
		$data['TotalAmplification'] = $this->report_twitter->activities($id, 'TotalAmplification', $date);
		$data['account'] = $id;
		$this->load->view('report/twitter/data/Activities', $data);
	}
	public function feeds($id)
	{
		$date = $_POST['date'];
		$data['date'] = $date;
		$data['AllAccount'] = $this->report_twitter->feeds($id, 'AllAccount', $date);
		$data['Category'] = $this->report_twitter->feeds($this->report_twitter->source($id), 'Category', $date);
		$data['AllTopPost'] = $this->report_twitter->feeds($id, 'AllTopPost', $date);
		$data['AllLeastPost'] = $this->report_twitter->feeds($id, 'AllLeastPost', $date);
		$data['NotLinkTopPost'] = $this->report_twitter->feeds($id, 'NotLinkTopPost', $date);
		$data['NotLinkLeastPost'] = $this->report_twitter->feeds($id, 'NotLinkLeastPost', $date);
		$data['LinkTopPost'] = $this->report_twitter->feeds($id, 'LinkTopPost', $date);
		$data['LinkLeastPost'] = $this->report_twitter->feeds($id, 'LinkLeastPost', $date);
		$data['ShareTopPost'] = $this->report_twitter->feeds($id, 'ShareTopPost', $date);
		$data['ShareLeastPost'] = $this->report_twitter->feeds($id, 'ShareLeastPost', $date);
		$data['LikeTopPost'] = $this->report_twitter->feeds($id, 'LikeTopPost', $date);
		$data['LikeLeastPost'] = $this->report_twitter->feeds($id, 'LikeLeastPost', $date);
		$data['CommentTopPost'] = $this->report_twitter->feeds($id, 'CommentTopPost', $date);
		$data['CommentLeastPost'] = $this->report_twitter->feeds($id, 'CommentLeastPost', $date);
		$data['account'] = $id;
		$this->load->view('report/twitter/data/Feeds', $data);
	}
	public function primetime($id)
	{
		$data['account'] = $id;
		$data['date'] = $_POST['date'];
		$data['unix'] = getmonth('unix', $data['date']);
		$this->load->view('report/twitter/data/PrimeTime', $data);
	}
	public function conversation($id)
	{
		$date = $_POST['date'];
		$data['account'] = $id;
		$data['date'] = $date;
		$data['data'] = $this->report_twitter->conversation($id, 'All', $date);		
		$this->load->view('report/twitter/data/Conversation', $data);
	}
	/** BELUM **/
	public function growth($id){
		$date = $_POST['date'];
		$temp = $this->report_twitter->highlight($id, 'Summary', $date);
		$data['account'] = array('account_id' => $id, 'name' => $temp[0]['name']);
		$data['date'] = $date;
		$data['unix'] = getmonth('unix', $date);
		$data['dailyFans'] = $this->report_twitter->growth($id, 'DailyFans', $date);
		$data['dailyGrowth'] = $this->report_twitter->growth($id, 'DailyFans', $date);
		$data['monthlyGrowthCompetitor'] = $this->report_twitter->summary($id, 'TopFollower', lastmonth());
		$data['data'] = $this->report_twitter->performance($id, 'Interaction', $date);
		$this->load->view('report/twitter/data/Growth', $data);
	}
}