<?php
class Blog_categories_model extends CI_Model {
	
	function get_blog_categories_list(){
		$this->db->from('blog_categories');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function generate_blog_category_sku($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}
	
	function get_blog_category_sku(){
		$blog_category_sku = $this->generate_blog_category_sku();
		$checktrue = $this->check_sku_exists($blog_category_sku);
		while ($checktrue == true){
			$blog_category_sku = $this->generate_blog_category_sku();
			$checktrue = $this->check_sku_exists($blog_category_sku);
		}
		return $blog_category_sku;
	}
	function check_sku_exists($sku){
		$this->db->from('blog_categories');
		$this->db->where( array('blog_category_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_blog_category_sku_code($blog_category_id){
		$sku_code = '';
		$this->db->from('blog_categories');
		$this->db->where( array('blog_category_id'=>$blog_category_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->blog_category_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_blog_category_sku();
		}

		return $sku_code;
	}

	function save($data){
		$insert = $this->db->insert('blog_categories', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			//$this->upload_cover_image($insert_id);

			$arr_return = array('res' => true,'dt' => 'Blog Category added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not add Blog Category successfully. Please try again.');
		}
		return $arr_return;
	}
	function blog_category_exists($blog_category_name){
		$this->db->where('blog_category_name',$blog_category_name);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('blog_categories');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_blog_category($blog_category_id){
		$this->db->from('blog_categories');
		$this->db->where( array('blog_category_id'=>$blog_category_id));
		return $this->db->get()->result_array();
	}
	function blog_category_update_exists($blog_category_id,$blog_category_name){
		$this->db->where('blog_category_id !=',$blog_category_id);
		$this->db->where('blog_category_name',$blog_category_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('blog_categories');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$blog_category_id){
		$this->db->where(array('blog_category_id'=>$blog_category_id));
		$update = $this->db->update('blog_categories', $data);
		if ($update){

			//$this->upload_cover_image($blog_category_id);
			$arr_return = array('res' => true,'dt' => 'Blog Category updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Blog Category successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($blog_category_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('blog_category_id'=>$blog_category_id));
		$delupdate = $this->db->update('blog_categories', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'Blog Category deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Blog Category');
		}
		return $arr_return;
	}

	function upload_cover_image($blog_category_id){
		if(basename($_FILES['cover_image']['name'])!=''){
			//$imagefilename = url_title(basename($_FILES['national_id']['name']),'-',TRUE);
			
			$upload_config['upload_path'] = './uploads/blog_category_cover_images/';
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
				$this->db->where(array('blog_category_id'=>$blog_category_id));				
				$this->db->update('blog_categories', array('cover_image' => $det['file_name']));
				$arr_return = array('res' => true,'dt' => 'Blog Category Cover Image uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => 'Blog Category Cover Image uploaded successfully');
		}
		return $arr_return;
	}


}