<?php

class Bitly_settings_model extends CI_Model {
	
	function get_bitly_settings(){
		$this->db->from('bitly_settings');
		return $this->db->get()->result();
	}
	function bitly_settings_exists(){
		$query = $this->db->get('bitly_settings');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('bitly_settings');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('bitly_settings', $data);
			if ($insert){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Bitly Settings saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully save Bitly Settings. Please try again.');
			}
		}else{
			$update = $this->db->update('bitly_settings', $data);
			if ($update){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Bitly Settings updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully update Bitly Settings. Please try again.');
			}
		}
		return $arr_return;
	}

}