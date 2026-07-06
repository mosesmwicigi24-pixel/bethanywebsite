<?php

class How_to_shop_model extends CI_Model {
	
	function get_how_to_shop(){
		$this->db->from('how_to_shop');
		return $this->db->get()->result();
	}
	function how_to_shop_exists(){
		$query = $this->db->get('how_to_shop');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('how_to_shop');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('how_to_shop', $data);
			if ($insert){

				$arr_return = array('res' => true,'dt' => 'How To Shop saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully save How To Shop. Please try again.');
			}
		}else{
			$update = $this->db->update('how_to_shop', $data);
			if ($update){

				$arr_return = array('res' => true,'dt' => 'How To Shop updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully update How To Shop. Please try again.');
			}
		}
		return $arr_return;
	}



}