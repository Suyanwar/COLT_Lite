<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Main extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_login');
	}
	public function index($id){
		$data['account_id'] = $id;
		$data['nama'] = $this->m_login->get_name($id)->name;
		$this->load->view('report/facebook/v_main', $data);
	}
}