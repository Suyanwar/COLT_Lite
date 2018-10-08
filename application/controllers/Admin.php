<?php 
class Admin extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('status') != "login"){
			redirect(base_url("login"));
		}
		$this->load->model('m_login');
	}
	function index(){
		$this->load->view('v_admin');
	}
	function load($id){
		$id = $_POST["id"];
		$this->load->view('v_tampil', array(
			'list_data' => $this->m_login->load_user($id)
			));
	}
}
?>