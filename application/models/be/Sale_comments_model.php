<?php

class Sale_comments_model extends CI_Model {
	
	function get_sale_comments(){
		$this->db->from('sale_comments');
		return $this->db->get()->result();
	}
	function sale_comments_exists(){
		$query = $this->db->get('sale_comments');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('sale_comments');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('sale_comments', $data);
			if ($insert){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Sale Comments saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully save Sale Comments. Please try again.');
			}
		}else{
			$update = $this->db->update('sale_comments', $data);
			if ($update){
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Sale Comments updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not successfully update Sale Comments. Please try again.');
			}
		}
		return $arr_return;
	}

}