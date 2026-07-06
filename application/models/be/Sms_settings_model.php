<?php

class Sms_settings_model extends CI_Model {
	
	function get_sms_settings(){
		$this->db->from('sms_settings');
		return $this->db->get()->result();
	}
	function sms_settings_exists(){
		$query = $this->db->get('sms_settings');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('sms_settings');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('sms_settings', $data);
			if ($insert){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> SMS Settings saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully save SMS Settings. Please try again.');
			}
		}else{
			$update = $this->db->update('sms_settings', $data);
			if ($update){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> SMS Settings updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully update SMS Settings. Please try again.');
			}
		}
		return $arr_return;
	}

}