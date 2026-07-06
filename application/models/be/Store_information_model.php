<?php

class Store_information_model extends CI_Model {
	
	function get_store_information(){
		$this->db->from('store_information');
		return $this->db->get()->result();
	}
	function store_information_exists(){
		$query = $this->db->get('store_information');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('store_information');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('store_information', $data);
			if ($insert){

				$this->upload_store_logo();

				$arr_return = array('res' => true,'dt' => 'Store Information saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully save Store Information. Please try again.');
			}
		}else{
			$update = $this->db->update('store_information', $data);
			if ($update){

				$this->upload_store_logo();
				
				$arr_return = array('res' => true,'dt' => 'Store Information updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully update Store Information. Please try again.');
			}
		}
		return $arr_return;
	}

	//UPLOAD contact LOGO
	function upload_store_logo(){
		if(basename($_FILES['store_logo']['name'])!=''){
			//$imagefilename = url_title(basename($_FILES['national_id']['name']),'-',TRUE);
			
			$upload_config['upload_path'] = './uploads/store_logo/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('store_logo');
		
			if($q){				
				$det = $this->upload->data();					
				$this->db->update('store_information', array('store_logo' => $det['file_name']));
				$arr_return = array('res' => true,'dt' => 'Logo set successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => 'Logo set successfully');
		}
		return $arr_return;
	}


}