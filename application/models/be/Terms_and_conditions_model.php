<?php

class Terms_and_conditions_model extends CI_Model {
	
	function get_terms_and_conditions(){
		$this->db->from('terms_and_conditions');
		return $this->db->get()->result();
	}
	function terms_and_conditions_exists(){
		$query = $this->db->get('terms_and_conditions');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('terms_and_conditions');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('terms_and_conditions', $data);
			if ($insert){

				$arr_return = array('res' => true,'dt' => 'Terms and Conditions saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully save Terms and Conditions. Please try again.');
			}
		}else{
			$update = $this->db->update('terms_and_conditions', $data);
			if ($update){

				$arr_return = array('res' => true,'dt' => 'Terms and Conditions updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully update Terms and Conditions. Please try again.');
			}
		}
		return $arr_return;
	}



}