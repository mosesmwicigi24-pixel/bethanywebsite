<?php

class Pesapal_settings_model extends CI_Model {
	
	function get_pesapal_settings(){
		$this->db->from('pesapal_settings');
		return $this->db->get()->result();
	}
	function pesapal_settings_exists(){
		$query = $this->db->get('pesapal_settings');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('pesapal_settings');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('pesapal_settings', $data);
			if ($insert){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Pesapal Settings saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully save Pesapal Settings. Please try again.');
			}
		}else{
			$update = $this->db->update('pesapal_settings', $data);
			if ($update){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Pesapal Settings updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully update Pesapal Settings. Please try again.');
			}
		}
		return $arr_return;
	}

}