<?php

class Seo_model extends CI_Model {
	
	function get_seo(){
		$this->db->from('seo');
		return $this->db->get()->result();
	}
	function seo_exists(){
		$query = $this->db->get('seo');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('seo');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('seo', $data);
			if ($insert){
				$arr_return = array('res' => true,'dt' => 'SEO Settings saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully save SEO Settings. Please try again.');
			}
		}else{
			$update = $this->db->update('seo', $data);
			if ($update){
				$arr_return = array('res' => true,'dt' => 'SEO Settings updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully update SEO Settings. Please try again.');
			}
		}
		return $arr_return;
	}


}