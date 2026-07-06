<?php

class Privacy_policy_model extends CI_Model {
	
	function get_privacy_policy(){
		$this->db->from('privacy_policy');
		return $this->db->get()->result();
	}
	function privacy_policy_exists(){
		$query = $this->db->get('privacy_policy');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('privacy_policy');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('privacy_policy', $data);
			if ($insert){

				$arr_return = array('res' => true,'dt' => 'Privacy Policy saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully save Privacy Policy. Please try again.');
			}
		}else{
			$update = $this->db->update('privacy_policy', $data);
			if ($update){

				$arr_return = array('res' => true,'dt' => 'Privacy Policy updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully update Privacy Policy. Please try again.');
			}
		}
		return $arr_return;
	}



}