<?php

class Email_notification_settings_model extends CI_Model {
	
	function get_email_notification_settings(){
		$this->db->from('email_notification_settings');
		return $this->db->get()->result();
	}
	function email_notification_settings_exists(){
		$query = $this->db->get('email_notification_settings');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('email_notification_settings');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('email_notification_settings', $data);
			if ($insert){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Email Notification Settings saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully save Email Notification Settings. Please try again.');
			}
		}else{
			$update = $this->db->update('email_notification_settings', $data);
			if ($update){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Email Notification Settings updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully update Email Notification Settings. Please try again.');
			}
		}
		return $arr_return;
	}

}