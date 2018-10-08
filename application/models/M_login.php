<?php 
class M_login extends CI_Model
{
	function cek_login($table,$where){		
		return $this->db->get_where($table,$where);
	}
	function load_user($id){
		$query = $this->db->query("select account_id, photo_cover, photo_profile, name, follower, socmed from account where socmed = '$id'");
		return $query->result();
	}
	function get_name($id){
		$query = $this->db->query("select name from account where account_id = '$id'");
		return $query->row();
	}
}
?>