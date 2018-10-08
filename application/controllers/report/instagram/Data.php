<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('report_instagram');
	}
	public function summary($id){
		$date = $_POST['date'];
		$data['account'] = $id;
		$period = getmonth('date', $date);
		$data['dates'][0] = DateTime::createFromFormat('Y-m-d', $period[1])->format('M Y');
		for ($i=1; $i < 4; $i++) { 
			$data['prevData'.$i] = $this->report_instagram->summary($id, 'Summary', date_format(date_sub(date_create($period[0]), date_interval_create_from_date_string($i." months")), 'd-m-Y')." ~ ".date_format(date_sub(date_create($period[1]), date_interval_create_from_date_string($i." months")), 'd-m-Y'));
			$data['dates'][$i] = date_format(date_sub(date_create($period[0]), date_interval_create_from_date_string($i." months")), 'M Y');
		}
		$data['date'] = $date;
		$data['data'] = $this->report_instagram->summary($id, 'Summary', $date);
		$this->load->view('report/instagram/data/Summary', $data);
	}
	public function primetime($id)
	{
		$data['account'] = $id;
		$data['date'] = $_POST['date'];
		$data['unix'] = getmonth('unix', $data['date']);
		$this->load->view('report/instagram/data/PrimeTime', $data);
	}

	public function feeds($id)
	{
		$date = $_POST['date'];
		$data['AllTopPost'] = $this->report_instagram->feeds($id, 'AllTopPost', $date);
		$data['AllLeastPost'] = $this->report_instagram->feeds($id, 'AllLeastPost', $date);
		$data['LikeTopPost'] = $this->report_instagram->feeds($id, 'LikeTopPost', $date);
		$data['LikeLeastPost'] = $this->report_instagram->feeds($id, 'LikeLeastPost', $date);
		$data['CommentTopPost'] = $this->report_instagram->feeds($id, 'CommentTopPost', $date);
		$data['CommentLeastPost'] = $this->report_instagram->feeds($id, 'CommentLeastPost', $date);
		$data['VideoTopPost'] = $this->report_instagram->feeds($id, 'VideoTopPost', $date);
		$data['VideoLeastPost'] = $this->report_instagram->feeds($id, 'VideoLeastPost', $date);
		$data['ViewVideoTopPost'] = $this->report_instagram->feeds($id, 'ViewVideoTopPost', $date);
		$data['ViewVideoLeastPost'] = $this->report_instagram->feeds($id, 'ViewVideoLeastPost', $date);
		$data['account'] = $id;
		$data['date'] = $date;
		$this->load->view('report/instagram/data/Feeds', $data);
	}
	public function users($id){
		$date = $_POST['date'];
		$data['account'] = $id;
		$data['date'] = $date;
		$data['mostActive'] = $this->report_instagram->users($id, 'MostActive', $date);
		$data['mostCommenters'] = $this->report_instagram->users($id, 'MostCommenters', $date);
		$data['mostLikers'] = $this->report_instagram->users($id, 'MostLikers', $date);		
		$this->load->view('report/instagram/data/Users', $data);
	}
	public function conversation($id)
	{
		$date = $_POST['date'];
		$data['account'] = $id;
		$data['date'] = $date;
		$data['data'] = $this->report_instagram->conversation($id, 'All', $date);		
		$this->load->view('report/instagram/data/Conversation', $data);
	}
	public function tagpost($id){
		$date = $_POST['date'];
		$data['account'] = $id;
		$data['date'] = $date;
		$data['unix'] = getmonth('unix', $date);
		$data['data'] = $this->report_instagram->hashtag($id, 'MostEngage', $date);		
		$this->load->view('report/instagram/data/TagPost', $data);
	}
	/** BELUM **/
	public function activity($id){
		$date = $_POST['date'];
		$temp = $this->report_instagram->highlight($id, 'Summary', $date);
		$data['account'] = array('account_id' => $id, 'name' => $temp[0]['name']);
		$data['date'] = $date;
		$data['unix'] = getmonth('unix', $date);
		$data['competitor'] = $this->report_instagram->performance($id, 'Interaction', $date);
		$data['activity'] = $this->report_instagram->activities($id, 'ActivitiesChart', $date);
		$data['detail'] = $this->report_instagram->activities($id, 'ActivitiesDetail', $date);	
		$this->load->view('report/instagram/data/Activity', $data);
	}
	public function growth($id){
		$date = $_POST['date'];
		$temp = $this->report_instagram->highlight($id, 'Summary', $date);
		$data['account'] = array('account_id' => $id, 'name' => $temp[0]['name']);
		$data['date'] = $date;
		$data['unix'] = getmonth('unix', $date);
		$data['dailyFans'] = $this->report_instagram->growth($id, 'DailyFans', $date);
		$data['dailyGrowth'] = $this->report_instagram->growth($id, 'DailyFans', $date);
		$data['monthlyGrowthCompetitor'] = $this->report_instagram->summary($id, 'TopFollower', lastmonth());
		$data['data'] = $this->report_instagram->performance($id, 'Interaction', $date);
		$this->load->view('report/instagram/data/Growth', $data);
	}
}