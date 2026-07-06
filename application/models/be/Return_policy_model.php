<?php

class Return_policy_model extends CI_Model {
	
	function get_return_policy(){
		$this->db->from('return_policy');
		return $this->db->get()->result();
	}
	function return_policy_exists(){
		$query = $this->db->get('return_policy');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('return_policy');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('return_policy', $data);
			if ($insert){

				$arr_return = array('res' => true,'dt' => 'Return Policy saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully save Return Policy. Please try again.');
			}
		}else{
			$update = $this->db->update('return_policy', $data);
			if ($update){

				$arr_return = array('res' => true,'dt' => 'Return Policy updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully update Return Policy. Please try again.');
			}
		}
		return $arr_return;
	}



}