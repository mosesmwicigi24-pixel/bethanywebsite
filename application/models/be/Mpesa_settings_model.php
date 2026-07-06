<?php

class Mpesa_settings_model extends CI_Model {
	
	function get_mpesa_settings(){
		$this->db->from('mpesa_settings');
		return $this->db->get()->result();
	}
	function mpesa_settings_exists(){
		$query = $this->db->get('mpesa_settings');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('mpesa_settings');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('mpesa_settings', $data);
			if ($insert){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> M-Pesa Settings saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully save M-Pesa Settings. Please try again.');
			}
		}else{
			$update = $this->db->update('mpesa_settings', $data);
			if ($update){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> M-Pesa Settings updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully update M-Pesa Settings. Please try again.');
			}
		}
		return $arr_return;
	}

}