<?php
class Suppliers_model extends CI_Model {

	function get_suppliers_list(){
		$this->db->from('suppliers');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function supplier_exists(){

		$supplier_code = $this->input->post('supplier_code');
		$supplier_name = $this->input->post('supplier_name');

    	$msg = '';
    	$msg2 = '';

    	//Supplier Name
    	$this->db->where(array('supplier_name' => $supplier_name, 'is_deleted' => 0));
		$query = $this->db->get('suppliers');

		if ($query->num_rows() > 0){
			$msg = '<i class="icon-cancel-circle2"></i> Duplicate Supplier Name: The Supplier Name you entered has already been defined.<br>';
		}else {
			if ($supplier_code != '' && $supplier_code != null){
		    	//Supplier Code
		    	$this->db->where(array('supplier_code' => $supplier_code, 'is_deleted' => 0));
				$query = $this->db->get('suppliers');

				if ($query->num_rows() > 0){
					$msg = '<i class="icon-cancel-circle2"></i> Duplicate Supplier Code: The Supplier Code you entered has already been defined.<br>';
				}
			}
		}

		if ($msg == $msg2){
			$arr_return = array('res' => true,'dt' => '');
		}else{
			$arr_return = array('res' => false,'dt' => $msg);
		}

		return $arr_return;

	}	

	function save($data){
		$insert = $this->db->insert('suppliers', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			//$this->upload_supplier_logo($insert_id);

			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Supplier added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Supplier successfully. Please try again.');
		}
		return $arr_return;
	}
	function get_supplier($supplier_id){
		$this->db->from('suppliers');
		$this->db->where( array('supplier_id'=>$supplier_id));
		return $this->db->get()->result();
	}
	function get_supplier2($supplier_id){
		$this->db->from('suppliers');
		$this->db->where( array('supplier_id'=>$supplier_id));
		return $this->db->get()->result_array();
	}
	function supplier_update_exists(){
		$supplier_id = $this->input->post('supplier_id');
		$supplier_code = $this->input->post('supplier_code');
		$supplier_name = $this->input->post('supplier_name');

    	$msg = '';
    	$msg2 = '';

    	//Supplier Name
    	$this->db->where(array('supplier_id != ' => $supplier_id, 'supplier_name' => $supplier_name, 'is_deleted' => 0));
		$query = $this->db->get('suppliers');

		if ($query->num_rows() > 0){
			$msg = 'Duplicate Supplier Name: The Supplier Name you entered has already been defined.<br>';
		}else {
			if ($supplier_code != '' && $supplier_code != null){
		    	//Supplier Code
		    	$this->db->where(array('supplier_id != ' => $supplier_id, 'supplier_code' => $supplier_code, 'is_deleted' => 0));
				$query = $this->db->get('suppliers');

				if ($query->num_rows() > 0){
					$msg = 'Duplicate Supplier Code: The Supplier Code you entered has already been defined.<br>';
				}
			}
		}

		if ($msg == $msg2){
			$arr_return = array('res' => true,'dt' => '');
		}else{
			$arr_return = array('res' => false,'dt' => $msg);
		}

		return $arr_return;
	}	
	function update($data,$supplier_id){
		$this->db->where(array('supplier_id'=>$supplier_id));
		$update = $this->db->update('suppliers', $data);
		if ($update){

			// $this->upload_supplier_logo($supplier_id);

			// //THUMBNAIL
			// $supplier = $this->get_supplier($supplier_id);
			// foreach ($supplier as $row) {
			// 	if ($row->logo != '' && file_exists("./uploads/supplier_logos/" . $row->logo) && $row->logo_thumb == '') {
			// 		$this->createLogoThumbnail($supplier_id, $row->logo);
			// 	}
			// }
			
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Supplier updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Supplier successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($supplier_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('supplier_id'=>$supplier_id));
		$delupdate = $this->db->update('suppliers', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Supplier deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Supplier');
		}
		return $arr_return;
	}

	function upload_supplier_logo($supplier_id){
		if(basename($_FILES['supplier_logo']['name'])!=''){
			//$imagefilename = url_title(basename($_FILES['national_id']['name']),'-',TRUE);
			
			$upload_config['upload_path'] = './uploads/supplier_logos/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('supplier_logo');
		
			if($q){				
				$det = $this->upload->data();	
				$this->db->where(array('supplier_id'=>$supplier_id));				
				$this->db->update('suppliers', array('logo' => $det['file_name']));
				$this->createLogoThumbnail($supplier_id, $det['file_name']);
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Supplier Logo uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => '<i class="icon-cancel-circle2"></i> Supplier Logo uploaded successfully');
		}
		return $arr_return;
	}

	function createLogoThumbnail($supplier_id, $filename){
      	$source_path = './uploads/supplier_logos/' . $filename;
      	$target_path = './uploads/supplier_logos/thumbs/';
      
      	$config_manip = array(
          	'image_library' => 'gd2',
          	'source_image' => $source_path,
          	'new_image' => $target_path,
          	'create_thumb'    => TRUE,
          	'maintain_ratio' => TRUE,
          	'width' => 500,
          	'height' => 360
      	);
   
      	$this->load->library('image_lib', $config_manip);
      	if ($this->image_lib->resize()) {
      		$source_image_name = $this->image_lib->dest_image;
        	$extension = strrchr($source_image_name , '.');
        	$name = substr($source_image_name , 0, -strlen($extension));
        	$logo_thumb_name = $name.'_thumb'.$extension;


			$this->db->where(array('supplier_id'=>$supplier_id));				
			$this->db->update('suppliers', array('logo_thumb' => $logo_thumb_name));
      	}   
      	$this->image_lib->clear();
   }

   function delete_logo($supplier_id) {
		$data = array(
			'logo'=> '',
			'logo_thumb'=> ''
		);	
		$this->db->where(array('supplier_id'=>$supplier_id));		
		$update = $this->db->update('suppliers', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Logo deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Logo. Please try again.');
		}
		return $arr_return;

   }


}