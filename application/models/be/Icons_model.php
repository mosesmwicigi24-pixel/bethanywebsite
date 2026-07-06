<?php
class Icons_model extends CI_Model {
	
	function get_icons_list(){
		$this->db->from('icons');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}


}