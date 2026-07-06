<?php
class Brands_model extends CI_Model {

	function get_brands_list(){
		$this->db->from('brands');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function generate_brand_sku($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}
	
	function get_brand_sku(){
		$brand_sku = $this->generate_brand_sku();
		$checktrue = $this->check_brand_sku_exists($brand_sku);
		while ($checktrue == true){
			$brand_sku = $this->generate_brand_sku();
			$checktrue = $this->check_brand_sku_exists($brand_sku);
		}
		return $brand_sku;
	}
	function check_brand_sku_exists($sku){
		$this->db->from('brands');
		$this->db->where( array('brand_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_brand_sku_code($brand_id){
		$sku_code = '';
		$this->db->from('brands');
		$this->db->where( array('brand_id'=>$brand_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->brand_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_brand_sku();
		}

		return $sku_code;
	}

	function save($data){
		$insert = $this->db->insert('brands', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			$this->upload_brand_logo($insert_id);

			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Brand added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Brand successfully. Please try again.');
		}
		return $arr_return;
	}
	function brand_exists($brand_name){
		$this->db->where('brand_name',$brand_name);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('brands');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_brand($brand_id){
		$this->db->from('brands');
		$this->db->where( array('brand_id'=>$brand_id));
		return $this->db->get()->result();
	}
	function get_brand2($brand_id){
		$this->db->from('brands');
		$this->db->where( array('brand_id'=>$brand_id));
		return $this->db->get()->result_array();
	}
	function brand_update_exists($brand_id,$brand_name){
		$this->db->where('brand_id !=',$brand_id);
		$this->db->where('brand_name',$brand_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('brands');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$brand_id){
		$this->db->where(array('brand_id'=>$brand_id));
		$update = $this->db->update('brands', $data);
		if ($update){

			$this->upload_brand_logo($brand_id);

			//THUMBNAIL
			$brand = $this->get_brand($brand_id);
			foreach ($brand as $row) {
				if ($row->logo != '' && file_exists("./uploads/brand_logos/" . $row->logo) && $row->logo_thumb == '') {
					$this->createLogoThumbnail($brand_id, $row->logo);
				}
			}
			
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Brand updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Brand successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($brand_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('brand_id'=>$brand_id));
		$delupdate = $this->db->update('brands', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Brand deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Brand');
		}
		return $arr_return;
	}

	function upload_brand_logo($brand_id){
		if(basename($_FILES['brand_logo']['name'])!=''){
			//$imagefilename = url_title(basename($_FILES['national_id']['name']),'-',TRUE);
			
			$upload_config['upload_path'] = './uploads/brand_logos/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('brand_logo');
		
			if($q){				
				$det = $this->upload->data();	
				$this->db->where(array('brand_id'=>$brand_id));				
				$this->db->update('brands', array('logo' => $det['file_name']));
				$this->createLogoThumbnail($brand_id, $det['file_name']);
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Brand Logo uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => '<i class="icon-cancel-circle2"></i> Brand Logo uploaded successfully');
		}
		return $arr_return;
	}

	function createLogoThumbnail($brand_id, $filename){
      	$source_path = './uploads/brand_logos/' . $filename;
      	$target_path = './uploads/brand_logos/thumbs/';
      
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


			$this->db->where(array('brand_id'=>$brand_id));				
			$this->db->update('brands', array('logo_thumb' => $logo_thumb_name));
      	}   
      	$this->image_lib->clear();
   }

   function delete_logo($brand_id) {
		$data = array(
			'logo'=> '',
			'logo_thumb'=> ''
		);	
		$this->db->where(array('brand_id'=>$brand_id));		
		$update = $this->db->update('brands', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Logo deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Logo. Please try again.');
		}
		return $arr_return;

   }


}