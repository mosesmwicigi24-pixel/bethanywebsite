<?php
class Departments_model extends CI_Model {
	
	function get_departments_list(){
		$this->db->from('departments');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function generate_department_sku($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}
	
	function get_department_sku(){
		$department_sku = $this->generate_department_sku();
		$checktrue = $this->check_sku_exists($department_sku);
		while ($checktrue == true){
			$department_sku = $this->generate_department_sku();
			$checktrue = $this->check_sku_exists($department_sku);
		}
		return $department_sku;
	}
	function check_sku_exists($sku){
		$this->db->from('departments');
		$this->db->where( array('department_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_department_sku_code($department_id){
		$sku_code = '';
		$this->db->from('departments');
		$this->db->where( array('department_id'=>$department_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->department_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_department_sku();
		}

		return $sku_code;
	}

	function save($data){
		$insert = $this->db->insert('departments', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			$this->upload_cover_image($insert_id);

			$arr_return = array('res' => true,'dt' => 'Department added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not add department successfully. Please try again.');
		}
		return $arr_return;
	}
	function department_exists($department_name){
		$this->db->where('department_name',$department_name);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('departments');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_department($department_id){
		$this->db->from('departments');
		$this->db->where( array('department_id'=>$department_id));
		return $this->db->get()->result();
	}
	function department_update_exists($department_id,$department_name){
		$this->db->where('department_id !=',$department_id);
		$this->db->where('department_name',$department_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('departments');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$department_id){
		$this->db->where(array('department_id'=>$department_id));
		$update = $this->db->update('departments', $data);
		if ($update){

			$this->upload_cover_image($department_id);

			//THUMBNAIL
			$department = $this->get_department($department_id);
			foreach ($department as $row) {
				if ($row->cover_image != '' && file_exists("./uploads/department_cover_images/" . $row->cover_image) && $row->thumb_image == '') {
					$this->createThumbnail($department_id, $row->cover_image);
				}
			}
			
			$arr_return = array('res' => true,'dt' => 'Department updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update department successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($department_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('department_id'=>$department_id));
		$delupdate = $this->db->update('departments', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'Department deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting department');
		}
		return $arr_return;
	}

	function upload_cover_image($department_id){
		if(basename($_FILES['cover_image']['name'])!=''){
			//$imagefilename = url_title(basename($_FILES['national_id']['name']),'-',TRUE);
			
			$upload_config['upload_path'] = './uploads/department_cover_images/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('cover_image');
		
			if($q){				
				$det = $this->upload->data();	
				$this->db->where(array('department_id'=>$department_id));				
				$this->db->update('departments', array('cover_image' => $det['file_name']));
				$this->createThumbnail($department_id, $det['file_name']);
				$arr_return = array('res' => true,'dt' => 'Department Cover Image uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => 'Department Cover Image uploaded successfully');
		}
		return $arr_return;
	}

	function createThumbnail($department_id, $filename){
      	$source_path = './uploads/department_cover_images/' . $filename;
      	$target_path = './uploads/department_cover_images/thumbs/';
      
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
        	$thumb_image_name = $name.'_thumb'.$extension;


			$this->db->where(array('department_id'=>$department_id));				
			$this->db->update('departments', array('thumb_image' => $thumb_image_name));
      	}   
      	$this->image_lib->clear();
   }

   function delete_cover_image($department_id) {
		$data = array(
			'cover_image'=> '',
			'thumb_image'=> ''
		);	
		$this->db->where(array('department_id'=>$department_id));		
		$update = $this->db->update('departments', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'Cover Image deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Cover Image. Please try again.');
		}
		return $arr_return;

   }


}